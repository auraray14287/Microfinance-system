<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\Group;
use App\Models\Member;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class TopGroupsByPortfolio extends BarChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Top Groups by Loan Portfolio';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 6;

    protected function getData(): array
    {
        $allowedIds = $this->allowedGroupIds();
        $groupQuery = Group::where('status', 'active');
        if ($allowedIds !== null) $groupQuery->whereIn('id', $allowedIds);
        $groups = $groupQuery->get(['id', 'name']);

        $map = [];
        foreach ($groups as $group) {
            $memberIds = Member::where('group_id', $group->id)->pluck('id');
            $balance   = (float) Loan::whereIn('member_id', $memberIds)
                ->whereIn('loan_status', ['approved', 'partially_paid'])
                ->sum('balance');
            if ($balance > 0) {
                $map[$group->name] = $balance;
            }
        }

        arsort($map);
        $map    = array_slice($map, 0, 5, true);
        $labels = array_keys($map);
        $values = array_values($map);

        return [
            'datasets' => [
                [
                    'label'           => 'Active Loan Balance (KES)',
                    'data'            => $values,
                    'backgroundColor' => [
                        'rgba(37,99,235,0.8)',
                        'rgba(59,130,246,0.75)',
                        'rgba(96,165,250,0.7)',
                        'rgba(147,197,253,0.7)',
                        'rgba(191,219,254,0.65)',
                    ],
                    'borderColor'  => '#2563eb',
                    'borderWidth'  => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels ?: ['No data'],
        ];
    }
}