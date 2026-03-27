<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Models\MemberDeposit;
use App\Models\Repayments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoDeductOverduePayments extends Command
{
    protected $signature = 'loans:auto-deduct-overdue';
    protected $description = 'Auto-deduct overdue loan payments from member deposit wallets after 7 days';

    public function handle(): void
    {
        $cutoff = Carbon::today()->subDays(7);

        // Get all active loans where next_payment_date is 7+ days ago
        $overdueLoans = Loan::whereIn('loan_status', ['approved', 'partially_paid', 'defaulted'])
            ->whereNotNull('next_payment_date')
            ->where('next_payment_date', '<=', $cutoff)
            ->where('balance', '>', 0)
            ->get();

        $processed = 0;
        $skipped = 0;

        foreach ($overdueLoans as $loan) {
            $memberId = $loan->member_id;
            if (!$memberId) { $skipped++; continue; }

            // Check deposit balance
            $depositBalance = MemberDeposit::getBalance($memberId);
            if ($depositBalance <= 0) { $skipped++; continue; }

            // Check if payment already made for this period
            $periodStart = Carbon::parse($loan->next_payment_date)->subWeeks(3);
            $alreadyPaid = Repayments::where('loan_id', $loan->id)
                ->whereDate('created_at', '>=', $periodStart)
                ->sum('payments');

            $expectedInstallment = (float)($loan->amount_per_installment ?? 0);
            $shortfall = max(0, $expectedInstallment - $alreadyPaid);

            if ($shortfall <= 0) { $skipped++; continue; }

            // Deduct from deposit — full if possible, partial if not
            $deductAmount = min($depositBalance, $shortfall);

            // Record repayment
            $newLoanBalance = max(0, (float)$loan->balance - $deductAmount);
            Repayments::create([
                'loan_id'          => $loan->id,
                'payments'         => $deductAmount,
                'balance'          => $newLoanBalance,
                'payments_method'  => 'deposit',
                'reference_number' => 'AUTO-DEDUCT-' . now()->format('YmdHis'),
                'loan_number'      => $loan->loan_number ?? 'N/A',
                'principal'        => $loan->principal_amount ?? 0,
                'transaction_code' => 'AUTO',
                'notes'            => 'Auto-deducted from deposit wallet for overdue payment',
            ]);

            // Update loan balance and status
            $loan->balance = $newLoanBalance;
            if ($newLoanBalance <= 0) {
                $loan->loan_status = 'fully_paid';
            }
            // Update next_payment_date if fully covered
            if ($deductAmount >= $shortfall) {
                $durationPeriod = $loan->duration_period ?? 'month(s)';
                $loan->next_payment_date = $durationPeriod === 'week(s)'
                    ? Carbon::parse($loan->next_payment_date)->addWeek()->format('Y-m-d')
                    : Carbon::parse($loan->next_payment_date)->addWeeks(3)->format('Y-m-d');
            }
            $loan->save();

            // Debit deposit wallet
            MemberDeposit::debit(
                $memberId,
                $deductAmount,
                'Auto-deducted for overdue loan ' . ($loan->loan_number ?? $loan->id),
                $loan->id
            );

            $this->info("Deducted KES {$deductAmount} from member {$memberId} for loan {$loan->loan_number}");
            $processed++;
        }

        $this->info("Done. {$processed} deductions made, {$skipped} loans skipped.");
        Log::info("AutoDeductOverduePayments: {$processed} deductions, {$skipped} skipped.");
    }
}
