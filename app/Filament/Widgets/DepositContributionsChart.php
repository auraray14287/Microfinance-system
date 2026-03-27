<?php
namespace App\Filament\Widgets;

use App\Models\MemberDeposit;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class DepositContributionsChart extends LineChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;

    protected static ?string $heading   = 'Deposit Account Contributions per Month';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 6;

    protected function getData(): array
    {
        $year = now()->year;
        $credits = [];
        $debits  = [];

        for ($month = 1; $month <= 12; $month++) {
            $credits[] = (float) MemberDeposit::where('type', 'credit')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');

            $debits[] = (float) MemberDeposit::where('type', 'debit')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Credits (KES)',
                    'data'            => $credits,
                    'borderColor'     => '#16a34a',
                    'backgroundColor' => 'rgba(22,163,74,0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'borderWidth'     => 2,
                    'pointRadius'     => 4,
                ],
                [
                    'label'           => 'Debits (KES)',
                    'data'            => $debits,
                    'borderColor'     => '#dc2626',
                    'backgroundColor' => 'rgba(220,38,38,0.07)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'borderWidth'     => 2,
                    'pointRadius'     => 4,
                ],
            ],
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];
    }
}
