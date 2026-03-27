<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class MemberGrowth extends LineChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Member Growth';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 8;

    protected function getData(): array
    {
        $year    = now()->year;
        $monthly = [];
        $cumulative = [];

        $running = (int) $this->scopeMembers(Member::query())
            ->whereYear('created_at', '<', $year)
            ->count();

        for ($month = 1; $month <= 12; $month++) {
            $new      = (int) $this->scopeMembers(Member::query())
                ->whereYear('created_at',  $year)
                ->whereMonth('created_at', $month)
                ->count();
            $monthly[]    = $new;
            $running     += $new;
            $cumulative[] = $running;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Total Members',
                    'data'            => $cumulative,
                    'borderColor'     => '#0891b2',
                    'backgroundColor' => 'rgba(8,145,178,0.08)',
                    'tension'         => 0.4,
                    'fill'            => true,
                ],
                [
                    'label'           => 'New This Month',
                    'data'            => $monthly,
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.05)',
                    'tension'         => 0.4,
                    'fill'            => false,
                    'borderDash'      => [5, 5],
                ],
            ],
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];
    }
}