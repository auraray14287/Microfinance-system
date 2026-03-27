<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\Group;
use App\Models\Member;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class DefaultersByGroup extends BarChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Defaulters by Group';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 9;

    protected function getData(): array
    {
        $allowedIds = $this->allowedGroupIds();
        $groupQuery = Group::where('status', 'active');
        if ($allowedIds !== null) $groupQuery->whereIn('id', $allowedIds);
        $groups = $groupQuery->get(['id', 'name']);

        $labels = [];
        $counts = [];

        foreach ($groups as $group) {
            $memberIds = Member::where('group_id', $group->id)->pluck('id');
            $count     = Loan::whereIn('member_id', $memberIds)
                ->where(function ($q) {
                    $q->where('loan_status', 'defaulted')
                      ->orWhere(function ($q2) {
                          $q2->whereIn('loan_status', ['approved', 'partially_paid'])
                             ->where('clearance_date', '<', now());
                      });
                })->count();

            if ($count > 0) {
                $labels[] = $group->name;
                $counts[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Defaulters',
                    'data'            => $counts ?: [0],
                    'backgroundColor' => 'rgba(220,38,38,0.75)',
                    'borderColor'     => '#dc2626',
                    'borderWidth'     => 1,
                    'borderRadius'    => 4,
                ],
            ],
            'labels' => $labels ?: ['No defaulters'],
        ];
    }
}