<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Group;
use App\Models\Saving;
use App\Models\Repayments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GroupPayment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationLabel = 'Payment';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.group-payment';

    // Step 1: Group search + autocomplete
    public ?string $group_search = null;
    public array $groupSuggestions = [];
    public ?Group $selectedGroup = null;
    public ?string $groupSearchError = null;
    public bool $showSuggestions = false;

    // Step 2: Payment table
    public array $paymentRows = [];
    public string $paymentDate = '';
    public bool $saved = false;
    public ?string $saveError = null;

    // Transaction code
    public ?string $transaction_code = null;
    public ?string $transactionCodeError = null;

    // Contact used to send money
    public ?string $contact = null;
    public ?string $contactError = null;

    // PDF generation flag
    public bool $showPdfReady = false;
    public ?int $savedSessionId = null;

    public function mount(): void
    {
        $this->paymentDate = now()->format('Y-m-d');
    }

    // ──────────────────────────────────────────────────────────
    // LIVE AUTOCOMPLETE
    // ─���────────────────────────────────────────────────────────
    public function updatedGroupSearch(): void
    {
        $this->selectedGroup    = null;
        $this->paymentRows      = [];
        $this->saved            = false;
        $this->showPdfReady     = false;
        $this->groupSearchError = null;

        $term = trim($this->group_search ?? '');

        if (strlen($term) < 1) {
            $this->groupSuggestions = [];
            $this->showSuggestions  = false;
            return;
        }

        $user  = auth()->user();
        $query = Group::where('name', 'like', '%' . $term . '%')
                      ->orderBy('name')
                      ->limit(8);

        if (!$user->hasRole('super_admin')) {
            $query->where('assigned_officer', $user->id);
        }

        $this->groupSuggestions = $query->get(['id', 'name', 'location', 'registration_number'])
            ->map(fn($g) => [
                'id'       => $g->id,
                'name'     => $g->name,
                'sub'      => trim(($g->registration_number ?? '') . ($g->location ? ' · ' . $g->location : '')),
            ])->toArray();

        $this->showSuggestions = count($this->groupSuggestions) > 0;
    }

    // ──────────────────────────────────────────────────────────
    // User clicked a suggestion
    // ──────────────────────────────────────────────────────────
    public function selectGroupSuggestion(int $groupId): void
    {
        $this->groupSuggestions = [];
        $this->showSuggestions  = false;
        $this->groupSearchError = null;
        $this->saved            = false;
        $this->showPdfReady     = false;

        $user  = auth()->user();
        $query = Group::with([
            'members' => function ($q) {
                $q->where('status', 'active')
                  ->with(['loans' => function ($lq) {
                      $lq->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
                  }]);
            }
        ])->where('id', $groupId);

        if (!$user->hasRole('super_admin')) {
            $query->where('assigned_officer', $user->id);
        }

        $group = $query->first();

        if (!$group) {
            $this->groupSearchError = 'Group not found or not assigned to you.';
            return;
        }

        $this->selectedGroup = $group;
        $this->group_search  = $group->name;
        $this->buildPaymentRows();
    }

    // ──────────────────────────────────────────────────────────
    // Manual search
    // ──────────────────────────────────────────────────────────
    public function searchGroup(): void
    {
        $this->groupSuggestions = [];
        $this->showSuggestions  = false;
        $this->selectedGroup    = null;
        $this->paymentRows      = [];
        $this->saved            = false;
        $this->showPdfReady     = false;
        $this->groupSearchError = null;

        $term = trim($this->group_search ?? '');

        if (!$term) {
            $this->groupSearchError = 'Please enter a group name.';
            return;
        }

        $user  = auth()->user();
        $query = Group::with([
            'members' => function ($q) {
                $q->where('status', 'active')
                  ->with(['loans' => function ($lq) {
                      $lq->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
                  }]);
            }
        ])->where('name', 'like', '%' . $term . '%');

        if (!$user->hasRole('super_admin')) {
            $query->where('assigned_officer', $user->id);
        }

        $group = $query->first();

        if (!$group) {
            $this->groupSearchError = 'No group found matching "' . $term . '".';
            return;
        }

        $this->selectedGroup = $group;
        $this->group_search  = $group->name;
        $this->buildPaymentRows();
    }

    // ──────────────────────────────────────────────────────────
    // Build payment rows
    // ──────────────────────────────────────────────────────────
    private function buildPaymentRows(): void
    {
        $this->paymentRows = [];

        foreach ($this->selectedGroup->members as $member) {
            $activeLoans   = $member->loans;
            $expectedTotal = $activeLoans->sum(fn($l) => (float)($l->amount_per_installment ?? 0));

            $loanDetails = $activeLoans->map(fn($loan) => [
                'id'                     => $loan->id,
                'loan_number'            => $loan->loan_number ?? '#' . $loan->id,
                'amount_per_installment' => (float)($loan->amount_per_installment ?? 0),
                'balance'                => (float)($loan->balance ?? 0),
                'loan_status'            => $loan->loan_status,
            ])->toArray();

            $overdueResult = \App\Services\OverdueCalculator::forMember($activeLoans);

            $this->paymentRows[] = [
                'member_id'        => $member->id,
                'name'             => trim("{$member->first_name} {$member->middle_name} {$member->last_name}"),
                'id_number'        => $member->id_number,
                'group_name'       => $this->selectedGroup->name,
                'savings_input'    => '',
                'loan_input'       => '',
                'expected_loan'    => $expectedTotal,
                'loan_details'     => $loanDetails,
                'has_active_loans' => $activeLoans->isNotEmpty(),
                'overdue'          => $overdueResult['amount'],
                'overdue_days'     => $overdueResult['days'],
            ];
        }
    }

    // ──────────────────────────────────────────────────────────
    // Validate transaction code
    // ──────────────────────────────────────────────────────────
    private function validateTransactionCode(): bool
    {
        $this->transactionCodeError = null;
        $code = trim($this->transaction_code ?? '');

        if (empty($code)) {
            $this->transactionCodeError = 'Transaction code is required.';
            return false;
        }

        // Check if code has been used before
        $used = DB::table('used_transaction_codes')->where('code', $code)->first();
        if ($used) {
            $this->transactionCodeError = 'This transaction code has already been used. Please enter a different code.';
            return false;
        }

        // Validate contact
        if (empty(trim($this->contact ?? ''))) {
            $this->contactError = 'Contact used to send money is required.';
            return false;
        }

        return true;
    }

    // ──────────────────────────────────────────────────────────
    // Save payments
    // ──────────────────────────────────────────────────────────
    public function savePayments(): void
    {
        $this->saveError    = null;
        $this->saved        = false;
        $this->showPdfReady = false;
        $this->contactError = null;

        if (!$this->selectedGroup) {
            $this->saveError = 'No group selected.';
            return;
        }

        // Validate transaction code first
        if (!$this->validateTransactionCode()) {
            return;
        }

        $code = trim($this->transaction_code);

        DB::beginTransaction();
        try {
            $sessionRef = 'GPS-' . $this->selectedGroup->id . '-' . now()->format('YmdHis');

            foreach ($this->paymentRows as &$row) {
                $memberId = $row['member_id'];

                $savingsAmount = is_numeric($row['savings_input']) ? (float)$row['savings_input'] : 0;
                if ($savingsAmount > 0) {
                    Saving::create([
                        'member_id'         => $memberId,
                        'group_id'          => $this->selectedGroup->id,
                        'amount'            => $savingsAmount,
                        'contribution_date' => $this->paymentDate,
                        'notes'             => 'Group payment session: ' . $sessionRef,
                        'transaction_code'  => $code,
                        'contact'           => $this->contact,
                    ]);
                }

                $loanPayment = is_numeric($row['loan_input']) ? (float)$row['loan_input'] : 0;
                if ($loanPayment > 0 && !empty($row['loan_details'])) {
                    $this->distributeAndRecordLoanPayment(
                        $row['loan_details'],
                        $loanPayment,
                        $row['expected_loan'],
                        $sessionRef,
                        $code
                    );
                }
            }
            unset($row);

            // Mark the transaction code as used
            DB::table('used_transaction_codes')->insert([
                'code'        => $code,
                'group_id'    => $this->selectedGroup->id,
                'session_ref' => $sessionRef,
                'used_at'     => now(),
            ]);

            DB::commit();
            $this->saved          = true;
            $this->showPdfReady   = true;
            $this->savedSessionId = $this->selectedGroup->id;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->saveError = 'Failed to save payments: ' . $e->getMessage();
            Log::error('GroupPayment savePayments error: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    // Ratio-based loan distribution
    // ──────────────────────────────────────────────────────────
    private function distributeAndRecordLoanPayment(
        array $loanDetails,
        float $totalPaid,
        float $expectedTotal,
        string $sessionRef,
        string $transactionCode
    ): void {
        if ($expectedTotal <= 0) {
            if (!empty($loanDetails)) {
                $this->recordRepayment($loanDetails[0]['id'], $totalPaid, $sessionRef, $transactionCode);
            }
            return;
        }

        foreach ($loanDetails as $detail) {
            $installment = (float)$detail['amount_per_installment'];
            if ($installment <= 0) continue;

            $ratio   = $installment / $expectedTotal;
            $share   = round($totalPaid * $ratio, 2);
            $deficit = round(($expectedTotal * $ratio) - $share, 2);

            $loan = Loan::find($detail['id']);
            if (!$loan) continue;

            $newBalance = (float)$loan->balance - $share;

            // Check for overpayment - credit excess to deposit wallet
            $actualPayment = $share;
            if ($newBalance < 0) {
                $excess = abs($newBalance);
                $actualPayment = $share - $excess;
                \App\Models\MemberDeposit::credit(
                    $loan->member_id,
                    $excess,
                    'Overpayment on loan ' . ($loan->loan_number ?? $loan->id),
                    $loan->id
                );
            }

            Repayments::create([
                'loan_id'          => $loan->id,
                'payments'         => $actualPayment,
                'balance'          => max(0, $newBalance),
                'payments_method'  => 'group_payment',
                'reference_number' => $sessionRef,
                'loan_number'      => $loan->loan_number ?? 'N/A',
                'principal'        => $loan->principal_amount ?? 0,
                'transaction_code' => $transactionCode,
                'contact'          => $this->contact,
            ]);

            if ($deficit > 0) {
                $loan->amount_per_installment = (float)($loan->amount_per_installment ?? 0) + $deficit;
            }

            $loan->balance     = max(0, $newBalance);
            $loan->loan_status = $newBalance <= 0 ? 'fully_paid' : 'partially_paid';
            $loan->save();
        }
    }

    private function recordRepayment(int $loanId, float $amount, string $ref, string $transactionCode): void
    {
        $loan = Loan::find($loanId);
        if (!$loan) return;

        $rawBalance = (float)$loan->balance - $amount;
        $newBalance = max(0, $rawBalance);

        // Check for overpayment
        if ($rawBalance < 0) {
            $excess = abs($rawBalance);
            \App\Models\MemberDeposit::credit(
                $loan->member_id,
                $excess,
                'Overpayment on loan ' . ($loan->loan_number ?? $loanId),
                $loanId
            );
        }

        Repayments::create([
            'loan_id'          => $loanId,
            'payments'         => $amount,
            'balance'          => $newBalance,
            'payments_method'  => 'group_payment',
            'reference_number' => $ref,
            'loan_number'      => $loan->loan_number ?? 'N/A',
            'principal'        => $loan->principal_amount ?? 0,
            'transaction_code' => $transactionCode,
            'contact'          => $this->contact,
        ]);

        $loan->balance     = $newBalance;
        $loan->loan_status = $newBalance <= 0 ? 'fully_paid' : 'partially_paid';
        $loan->save();
    }

    // ── Computed totals ────────��───────────────────────────────
    public function getTotalSavingsProperty(): float
    {
        return collect($this->paymentRows)->sum(fn($r) => is_numeric($r['savings_input']) ? (float)$r['savings_input'] : 0);
    }

    public function getTotalLoanPaymentsProperty(): float
    {
        return collect($this->paymentRows)->sum(fn($r) => is_numeric($r['loan_input']) ? (float)$r['loan_input'] : 0);
    }

    public function getTotalExpectedLoanProperty(): float
    {
        return collect($this->paymentRows)->sum(fn($r) => (float)$r['expected_loan']);
    }

    // ── Reset ──────────────────────────────────────────────────
    public function resetAll(): void
    {
        $this->group_search        = null;
        $this->groupSuggestions    = [];
        $this->showSuggestions     = false;
        $this->selectedGroup       = null;
        $this->paymentRows         = [];
        $this->paymentDate         = now()->format('Y-m-d');
        $this->saved               = false;
        $this->saveError           = null;
        $this->showPdfReady        = false;
        $this->savedSessionId      = null;
        $this->groupSearchError    = null;
        $this->transaction_code    = null;
        $this->transactionCodeError = null;
        $this->contact              = null;
        $this->contactError         = null;
    }
}
