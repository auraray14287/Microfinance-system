<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Repayments;
use Carbon\Carbon;

class OverdueCalculator
{
    public static function forLoan(Loan $loan): array
    {
        $today = Carbon::today();

        $nextPayment = $loan->next_payment_date
            ? Carbon::parse($loan->next_payment_date)
            : null;

        if (!$nextPayment || !$nextPayment->lt($today)) {
            return ['amount' => 0.0, 'days' => 0];
        }

        $installment = (float)($loan->amount_per_installment ?? 0);
        $isWeekly    = str_contains(strtolower($loan->duration_period ?? ''), 'week');
        $stepWeeks   = $isWeekly ? 1 : 3;

        $releaseDate = $loan->loan_release_date
            ? Carbon::parse($loan->loan_release_date)
            : Carbon::parse($loan->created_at);

        $periods = [];
        $cursor  = $nextPayment->copy();

        for ($i = 0; $i < 52; $i++) {
            $periodStart = $cursor->copy()->subWeeks($stepWeeks);

            if ($periodStart->lt($releaseDate)) {
                break;
            }

            $periods[] = [
                'start' => $periodStart,
                'end'   => $cursor->copy()->subDay(),
                'due'   => $cursor->copy(),
            ];

            $cursor->subWeeks($stepWeeks);
        }

        $periods = array_reverse($periods);

        $allRepayments = Repayments::where('loan_id', $loan->id)
            ->orderBy('created_at')
            ->get(['payments', 'created_at']);

        $runningOverdue  = 0.0;
        $firstOverdueDay = null;

        foreach ($periods as $period) {
            $periodDue = $period['due'];

            if (!$periodDue->lt($today)) {
                continue;
            }

            $paidThisPeriod = $allRepayments
                ->filter(fn($r) =>
                    Carbon::parse($r->created_at)->gte($period['start']) &&
                    Carbon::parse($r->created_at)->lte($period['end'])
                )
                ->sum('payments');

            $expectedThisPeriod = $installment + $runningOverdue;
            $shortfall          = max(0.0, $expectedThisPeriod - (float)$paidThisPeriod);

            if ($shortfall > 0) {
                $runningOverdue = $shortfall;
                if ($firstOverdueDay === null) {
                    $firstOverdueDay = $periodDue->copy()->addDay();
                }
            } else {
                $runningOverdue  = 0.0;
                $firstOverdueDay = null;
            }
        }

        $overdueDays = 0;
        if ($runningOverdue > 0 && $firstOverdueDay !== null) {
            $overdueDays = (int)$firstOverdueDay->diffInDays($today);
        }

        return [
            'amount' => round($runningOverdue, 2),
            'days'   => $overdueDays,
        ];
    }

    public static function forMember(iterable $loans): array
    {
        $totalAmount = 0.0;
        $oldestDays  = 0;

        foreach ($loans as $loan) {
            $result       = self::forLoan($loan);
            $totalAmount += $result['amount'];
            if ($result['days'] > $oldestDays) {
                $oldestDays = $result['days'];
            }
        }

        return [
            'amount' => round($totalAmount, 2),
            'days'   => $oldestDays,
        ];
    }
}
