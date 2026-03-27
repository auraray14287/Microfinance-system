<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use App\Models\Member;
use App\Models\MemberDeposit;
use App\Models\Loan;
use App\Models\Repayments;
use Carbon\Carbon;

class MemberDepositPage extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationLabel = 'Deposit';
    protected static ?string $title = 'Deposit';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.member-deposit';

    public ?string $id_number = null;
    public ?Member $member = null;
    public ?float $depositBalance = null;
    public array $pendingLoans = [];
    public array $depositHistory = [];

    public ?float $amount = null;
    public ?string $reference = null;
    public ?string $notes = null;
    public ?string $contact = null;
    public ?string $saveError = null;
    public bool $saved = false;

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\TextInput::make('id_number')
                ->label('Member ID Number')
                ->placeholder('Enter member ID and press Enter')
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state) {
                    if ($state) {
                        $this->member = Member::where('id_number', $state)->first();
                        if ($this->member) {
                            $this->depositBalance = MemberDeposit::getBalance($this->member->id);
                            $this->loadPendingLoans();
                            $this->loadDepositHistory();
                        } else {
                            $this->member = null;
                            $this->depositBalance = null;
                            $this->pendingLoans = [];
                            $this->depositHistory = [];
                        }
                    }
                }),
        ]);
    }

    private function loadPendingLoans(): void
    {
        if (!$this->member) return;
        $currentBalance = MemberDeposit::getBalance($this->member->id);
        $this->pendingLoans = Loan::where('member_id', $this->member->id)
            ->whereIn('loan_status', ['pending', 'requested'])
            ->whereIn('loan_type_id', [5, 6])
            ->get()
            ->map(fn($loan) => [
                'id'          => $loan->id,
                'loan_number' => $loan->loan_number,
                'principal'   => $loan->principal_amount,
                'required'    => (float)($loan->deposit ?? 0),
                'paid'        => MemberDeposit::where('member_id', $this->member->id)
                                    ->where('loan_id', $loan->id)
                                    ->where('type', 'debit')
                                    ->sum('amount'),
                'shortfall'   => max(0, (float)($loan->deposit ?? 0) - MemberDeposit::where('member_id', $this->member->id)->where('loan_id', $loan->id)->where('type', 'debit')->sum('amount')),
                'wallet'      => $currentBalance,
                'status'      => $loan->loan_status,
            ])->toArray();
    }

    private function loadDepositHistory(): void
    {
        if (!$this->member) return;
        $this->depositHistory = MemberDeposit::where('member_id', $this->member->id)
            ->with('createdBy', 'loan')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn($d) => [
                'date'          => $d->created_at->format('d M Y H:i'),
                'type'          => $d->type,
                'amount'        => $d->amount,
                'balance_after' => $d->balance_after,
                'notes'         => $d->notes,
                'reference'     => $d->reference ?? '-',
                'contact'       => $d->contact ?? '-',
                'officer'       => $d->createdBy?->name ?? '-',
                'loan'          => $d->loan?->loan_number ?? '-',
            ])->toArray();
    }

    public function addDeposit(): void
    {
        $this->saveError = null;
        $this->saved = false;

        if (!$this->member) {
            $this->saveError = 'No member selected.';
            return;
        }

        $amount = (float)($this->amount ?? 0);
        if ($amount <= 0) {
            $this->saveError = 'Please enter a valid deposit amount.';
            return;
        }
        if (empty($this->reference)) {
            $this->saveError = 'Transaction reference is required.';
            return;
        }
        if (empty($this->contact)) {
            $this->saveError = 'Contact is required.';
            return;
        }

        $remainingAmount = $amount;

        // Step 1: Check for overdue loans and deduct first
        $overdueLoans = Loan::where('member_id', $this->member->id)
            ->whereIn('loan_status', ['partially_paid', 'approved', 'defaulted'])
            ->where('next_payment_date', '<', Carbon::today())
            ->orderBy('next_payment_date')
            ->get();

        foreach ($overdueLoans as $loan) {
            if ($remainingAmount <= 0) break;

            $overduePortion = (float)($loan->amount_per_installment ?? 0);
            $lastPayment = Repayments::where('loan_id', $loan->id)
                ->whereDate('created_at', '>=', Carbon::parse($loan->next_payment_date)->subWeeks(3))
                ->sum('payments');
            $overdueAmount = max(0, $overduePortion - $lastPayment);

            if ($overdueAmount > 0) {
                $deduction = min($remainingAmount, $overdueAmount);
                // Record repayment for overdue loan
                $newBalance = max(0, (float)$loan->balance - $deduction);
                Repayments::create([
                    'loan_id'         => $loan->id,
                    'payments'        => $deduction,
                    'balance'         => $newBalance,
                    'payments_method' => 'deposit',
                    'reference_number'=> 'DEP-' . now()->format('YmdHis'),
                    'loan_number'     => $loan->loan_number,
                    'principal'       => $loan->principal_amount,
                    'transaction_code'=> $this->reference ?? '',
                    'notes'           => 'Deducted from deposit to cover overdue payment',
                ]);
                $loan->balance = $newBalance;
                $loan->loan_status = $newBalance <= 0 ? 'fully_paid' : $loan->loan_status;
                $loan->save();

                MemberDeposit::debit($this->member->id, $deduction,
                    'Overdue deduction for loan ' . $loan->loan_number, $loan->id);
                $remainingAmount -= $deduction;
            }
        }

        // Step 2: Credit remaining to deposit account
        if ($remainingAmount > 0) {
            MemberDeposit::credit($this->member->id, $remainingAmount,
                $this->notes ?? 'Deposit contribution', null, null, $this->reference, $this->contact);
        }

        // Step 3: Check pending loans and auto-approve if balance sufficient
        $this->depositBalance = MemberDeposit::getBalance($this->member->id);
        $pendingLoans = Loan::where('member_id', $this->member->id)
            ->whereIn('loan_status', ['pending', 'requested'])
            ->whereIn('loan_type_id', [5, 6])
            ->get();

        foreach ($pendingLoans as $loan) {
            $required = (float)($loan->deposit ?? 0);
            // Calculate how much deposit has already been applied to this loan
            $alreadyDebited = MemberDeposit::where('member_id', $this->member->id)
                ->where('loan_id', $loan->id)
                ->where('type', 'debit')
                ->sum('amount');
            $shortfall = max(0, $required - $alreadyDebited);

            if ($shortfall <= 0 || $this->depositBalance >= $shortfall) {
                $deductNow = min($this->depositBalance, $shortfall);
                if ($deductNow > 0) {
                    MemberDeposit::debit($this->member->id, $deductNow,
                        'Deposit for loan ' . $loan->loan_number, $loan->id);
                }
                // Auto-approve and update release date to today
                $loan->loan_status = 'approved';
                $loan->loan_release_date = now()->format('Y-m-d');
                // Recalculate next payment and clearance dates
                $duration = (int)($loan->loan_duration ?? 0);
                $period = $loan->duration_period ?? 'month(s)';
                if ($duration > 0) {
                    $loan->next_payment_date = $period === 'week(s)'
                        ? now()->addWeek()->format('Y-m-d')
                        : now()->addWeeks(3)->format('Y-m-d');
                    $loan->clearance_date = $period === 'week(s)'
                        ? now()->addWeeks($duration)->format('Y-m-d')
                        : now()->addWeeks($duration * 3)->format('Y-m-d');
                }
                $loan->save();
                $this->depositBalance = MemberDeposit::getBalance($this->member->id);
            } elseif ($this->depositBalance > 0) {
                // Partial — debit what's available
                MemberDeposit::debit($this->member->id, $this->depositBalance,
                    'Partial deposit for loan ' . $loan->loan_number, $loan->id);
                $this->depositBalance = 0;
            }
        }

        $this->amount = null;
        $this->reference = null;
        $this->contact = null;
        $this->notes = null;
        $this->saved = true;
        $this->loadDepositHistory();
        $this->loadPendingLoans();

        Notification::make()->success()->title('Deposit recorded successfully.')->send();
    }

    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->label('Clear')
            ->color('gray')
            ->action(function () {
                $this->id_number = null;
                $this->member = null;
                $this->depositBalance = null;
                $this->pendingLoans = [];
                $this->depositHistory = [];
                $this->form->fill();
            });
    }
}
