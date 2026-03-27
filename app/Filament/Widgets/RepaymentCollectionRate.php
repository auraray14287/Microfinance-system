<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\Repayments;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class RepaymentCollectionRate extends LineChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Repayment Collection Rate (%)';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 7;

    protected function getData(): array
    {
        $year  = now()->year;
        $rates = [];

        // Total expected per month = sum of installment amounts for all active loans
        $expectedMonthly = (float) $this->scopeLoans(Loan::query())
            ->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted'])
            ->sum('amount_per_installment');

        for ($month = 1; $month <= 12; $month++) {
            $collected = (float) $this->scopeRepayments(Repayments::query())
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('payments');

            $rates[] = $expectedMonthly > 0
                ? round(min(($collected / $expectedMonthly) * 100, 100), 1)
                : 0;
        }

        return [
            'datasets' => [
                [
                    'label'                => 'Collection Rate (%)',
                    'data'                 => $rates,
                    'borderColor'          => '#7c3aed',
                    'backgroundColor'      => 'rgba(124,58,237,0.08)',
                    'tension'              => 0.4,
                    'fill'                 => true,
                    'pointBackgroundColor' => '#7c3aed',
                ],
            ],
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];
    }
}