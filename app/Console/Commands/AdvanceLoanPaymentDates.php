<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdvanceLoanPaymentDates extends Command
{
    protected $signature   = 'loans:advance-payment-dates';
    protected $description = 'Advance next_payment_date for all active loans where the due date has passed';

    public function handle(): void
    {
        $today    = Carbon::today();
        $advanced = 0;

        $loans = Loan::whereIn('loan_status', ['approved', 'partially_paid', 'defaulted'])
            ->whereNotNull('next_payment_date')
            ->where('next_payment_date', '<', $today->toDateString())
            ->get();

        foreach ($loans as $loan) {
            $next = Carbon::parse($loan->next_payment_date);

            $isWeekly = str_contains(strtolower($loan->duration_period ?? ''), 'week');
            $step     = $isWeekly ? 1 : 3;

            while ($next->lessThan($today)) {
                $next->addWeeks($step);
            }

            $old = $loan->next_payment_date;
            $loan->next_payment_date = $next->toDateString();
            $loan->save();

            $advanced++;

            Log::info(
                "loans:advance-payment-dates | Loan #{$loan->id} " .
                "({$loan->loan_number}) | {$old} => {$loan->next_payment_date}"
            );
        }

        $this->info("Done. Loans advanced: {$advanced}");
        Log::info("loans:advance-payment-dates | Complete. Advanced: {$advanced}");
    }
}
