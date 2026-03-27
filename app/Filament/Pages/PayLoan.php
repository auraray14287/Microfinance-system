<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayLoan extends Page
{
    protected static ?string $navigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationLabel = 'Pay Loan';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.pay-loan';

    // ── Hide from sidebar entirely ──────────────────────────────
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?string $member_id_number = null;
    public ?Member $selectedMember = null;
    public ?int $selectedLoanId = null;
    public ?Loan $selectedLoan = null;
    public float $paymentAmount = 0;
    public string $paymentMethod = '';
    public string $referenceNumber = '';
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function searchMember(): void
    {
        $this->selectedMember = null;
        $this->selectedLoanId = null;
        $this->selectedLoan = null;

        if (!$this->member_id_number) {
            return;
        }

        $user = auth()->user();
        $query = Member::with(['groups', 'loans' => function ($q) {
            $q->with('loan_type')->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
        }])->where('id_number', $this->member_id_number);

        if (!$user->hasRole('super_admin')) {
            $groupIds = Group::where('assigned_officer', $user->id)->pluck('id');
            $query->whereIn('group_id', $groupIds);
        }

        $this->selectedMember = $query->first();
    }

    public function selectLoan(int $loanId): void
    {
        $this->selectedLoan = Loan::with('loan_type', 'member')->findOrFail($loanId);
        $this->selectedLoanId = $loanId;
        $this->paymentAmount = 0;
        $this->paymentMethod = '';
        $this->referenceNumber = '';
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function goBack(): void
    {
        $this->selectedLoanId = null;
        $this->selectedLoan = null;
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function submitPayment(): void
    {
        if (!$this->paymentAmount || $this->paymentAmount <= 0) {
            $this->errorMessage = 'Please enter a valid payment amount greater than 0.';
            return;
        }
        if (!$this->paymentMethod) {
            $this->errorMessage = 'Please select a payment method.';
            return;
        }

        $loan = $this->selectedLoan->refresh();
        $payment_amount = (float) $this->paymentAmount;
        $old_balance = (float) $loan->balance;

        if ($payment_amount > $old_balance) {
            $this->errorMessage = 'Payment amount cannot exceed the outstanding balance of KES ' . number_format($old_balance, 2);
            return;
        }

        $template_content = \App\Models\LoanSettlementForms::latest()->first();
        if (!$template_content) {
            $this->errorMessage = 'No loan settlement form template found. Please create one first.';
            return;
        }

        try {
            $wallet = \Bavix\Wallet\Models\Wallet::where('name', $loan->from_this_account)
                ->where('organization_id', auth()->user()->organization_id)
                ->first();

            $principal_amount = $loan->principal_amount;
            $loan_number = $loan->loan_number;

            $is_early_settlement = false;
            $early_settlement_discount = 0;
            $adjusted_interest = $loan->interest_amount;
            $will_fully_settle = ($old_balance - $payment_amount) <= 0;

            if ($will_fully_settle && $old_balance > 0 && Carbon::parse($loan->loan_due_date)->isFuture()) {
                $interest_amount = $loan->interest_amount;
                $early_repayment_percent = $loan->loan_type->early_repayment_percent ?? 0;

                if ($early_repayment_percent > 0 && $interest_amount > 0) {
                    $is_early_settlement = true;
                    $early_settlement_discount = ($interest_amount * $early_repayment_percent) / 100;
                    $adjusted_interest = $interest_amount - $early_settlement_discount;

                    $loan->interest_amount = $adjusted_interest;
                    $loan->is_early_settlement = 1;
                    $loan->save();
                }
            }

            if ($is_early_settlement) {
                $adjusted_loan_total = $principal_amount + $adjusted_interest;
                $new_balance = $adjusted_loan_total - $payment_amount;
            } else {
                $new_balance = $old_balance - $payment_amount;
            }

            $repayment = \App\Models\Repayments::create([
                'loan_id' => $loan->id,
                'payments' => $payment_amount,
                'balance' => $new_balance,
                'payments_method' => $this->paymentMethod,
                'reference_number' => $this->referenceNumber ?: 'No reference was entered by ' . auth()->user()->name . ' - ' . auth()->user()->email,
                'loan_number' => $loan_number,
                'principal' => $principal_amount,
            ]);

            if ($wallet) {
                $wallet->deposit($payment_amount, ['meta' => 'Loan repayment amount']);
            }

            if ($new_balance <= 0) {
                $settlement_file_path = $this->generateSettlementForm($loan);
                $loan->update([
                    'balance' => 0,
                    'loan_status' => 'fully_paid',
                    'loan_settlement_file_path' => $settlement_file_path,
                ]);
            } else {
                $loan->update([
                    'balance' => $new_balance,
                    'loan_status' => 'partially_paid',
                ]);
            }

            $borrower = \App\Models\Borrower::find($loan->borrower_id);
            if ($borrower) {
                $this->sendSmsNotification($borrower, $loan, $payment_amount);
                if (!is_null($borrower->email)) {
                    $this->sendEmailNotification($borrower, $loan, $payment_amount);
                }
            }

            $this->successMessage = 'Payment of KES ' . number_format($payment_amount, 2) . ' recorded successfully! New balance: KES ' . number_format(max(0, $new_balance), 2);
            $this->selectedLoanId = null;
            $this->selectedLoan = null;
            $this->paymentAmount = 0;
            $this->paymentMethod = '';
            $this->referenceNumber = '';

            if ($this->selectedMember) {
                $this->selectedMember = Member::with(['groups', 'loans' => function ($q) {
                    $q->with('loan_type')->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
                }])->find($this->selectedMember->id);
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Payment failed: ' . $e->getMessage();
            Log::error('PayLoan submitPayment error: ' . $e->getMessage());
        }
    }

    public function resetAll(): void
    {
        $this->member_id_number = null;
        $this->selectedMember = null;
        $this->selectedLoanId = null;
        $this->selectedLoan = null;
        $this->paymentAmount = 0;
        $this->paymentMethod = '';
        $this->referenceNumber = '';
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    protected function generateSettlementForm($loan): ?string
    {
        try {
            $borrower = \App\Models\Borrower::findOrFail($loan->borrower_id);
            $company_name = auth()->user()->organization->name ?? auth()->user()->name;
            $company_address = auth()->user()->organization->address ?? 'Lusaka, Zambia';
            $borrower_name = $borrower->first_name . ' ' . $borrower->last_name;
            $borrower_phone = $borrower->mobile ?? '';
            $loan_amount = $loan->repayment_amount;
            $settled_date = date('d F Y');
            $current_date = date('d F Y');

            $template_content = \App\Models\LoanSettlementForms::latest()->first()->loan_settlement_text;

            $replacements = [
                '{company_name}' => $company_name,
                '{company_address}' => $company_address,
                '{customer_name}' => $borrower_name,
                '{customer_address}' => $borrower_phone,
                '{loan_amount}' => $loan_amount,
                '{settled_date}' => $settled_date,
                '{current_date}' => $current_date,
            ];

            $template_content = str_replace(array_keys($replacements), array_values($replacements), $template_content);
            $characters_to_remove = ['<br>', '&nbsp;'];
            $template_content = str_replace($characters_to_remove, '', $template_content);

            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $template_content, false, false);

            $current_year = date('Y');
            $path = public_path('LOAN_SETTLEMENT_FORMS/' . $current_year . '/DOCX');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file_name = \Illuminate\Support\Str::random(40) . '.docx';
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($path . '/' . $file_name);

            return 'LOAN_SETTLEMENT_FORMS/' . $current_year . '/DOCX/' . $file_name;
        } catch (\Exception $e) {
            Log::error('Settlement form generation error: ' . $e->getMessage());
            return null;
        }
    }

    protected function sendSmsNotification($borrower, $loan, $repaymentAmount): void
    {
        $bulk_sms_config = \App\Models\ThirdParty::withoutGlobalScope('org')
            ->where('name', 'SWIFT-SMS')->latest()->first();

        if (
            $bulk_sms_config &&
            $bulk_sms_config->is_active == 'Active' &&
            isset($borrower->mobile) &&
            isset($bulk_sms_config->base_uri) &&
            isset($bulk_sms_config->endpoint) &&
            isset($bulk_sms_config->token) &&
            isset($bulk_sms_config->sender_id)
        ) {
            $url = $bulk_sms_config->base_uri . $bulk_sms_config->endpoint;
            if ($url && $bulk_sms_config->token && $bulk_sms_config->sender_id) {
                $message = 'Hi ' . $borrower->first_name . ', We have received your repayment of K' .
                    number_format($repaymentAmount, 2) . '. Your updated balance is K' .
                    number_format($loan->balance, 2) . '. Thank you for your payment.';

                $jsonData = [
                    'sender_id' => $bulk_sms_config->sender_id,
                    'numbers'   => $borrower->mobile,
                    'message'   => $message,
                ];

                try {
                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bearer ' . $bulk_sms_config->token,
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                    ])->timeout(300)->withBody(json_encode($jsonData), 'application/json')->get($url);
                } catch (\Exception $e) {
                    Log::error('Failed to send SMS: ' . $e->getMessage());
                }
            }
        }
    }

    protected function sendEmailNotification($borrower, $loan, $repaymentAmount): void
    {
        try {
            $message = 'Hi ' . $borrower->first_name . ', We have received your repayment of K' .
                number_format($repaymentAmount, 2) . '. Your updated balance is K' .
                number_format($loan->balance, 2) . '. Thank you for your payment.';

            $borrower->notify(new \App\Notifications\LoanStatusNotification($message));
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}