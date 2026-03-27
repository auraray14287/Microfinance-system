<?php

namespace App\Filament\Widgets;

use App\Models\Saving;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class SavingsPerMonth extends BarChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Savings Collected per Month';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 5;

    protected function getData(): array
    {
        $year = now()->year;
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = (float) $this->scopeSavings(Saving::query())
                ->whereYear('contribution_date', $year)
                ->whereMonth('contribution_date', $month)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Savings (KES)',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(22,163,74,0.75)',
                    'borderColor'     => '#16a34a',
                    'borderWidth'     => 1,
                    'borderRadius'    => 4,
                ],
            ],
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];
    }
}