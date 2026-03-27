<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class TotalCollected extends ChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $heading   = 'Loan Status Breakdown';
    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 5;

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate']   ?? null;

        $loanQ = $this->scopeLoans(Loan::query())
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate));

        $statuses = [
            'partially_paid' => ['label' => 'Partially Paid', 'color' => '#f59e0b'],
            'defaulted'      => ['label' => 'Defaulted',      'color' => '#dc2626'],
            'fully_paid'     => ['label' => 'Fully Paid',     'color' => '#16a34a'],
            'approved'       => ['label' => 'Approved',       'color' => '#3b82f6'],
        ];

        $data   = [];
        $labels = [];
        $colors = [];

        foreach ($statuses as $key => $meta) {
            $count = (clone $loanQ)->where('loan_status', $key)->count();
            if ($count > 0) {
                $data[]   = $count;
                $labels[] = $meta['label'] . ' (' . $count . ')';
                $colors[] = $meta['color'];
            }
        }

        if (empty($data)) {
            $data   = [1];
            $labels = ['No loans'];
            $colors = ['#e5e7eb'];
        }

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => $colors,
                    'hoverOffset'     => 8,
                    'borderWidth'     => 2,
                    'borderColor'     => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string { return 'pie'; }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => [
                        'padding'  => 16,
                        'boxWidth' => 14,
                        'font'     => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'enabled'   => true,
                    'callbacks' => [
                        // Show: "Partially Paid (1): 50%"
                        'label' => "function(context) {
                            var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                            var value = context.parsed;
                            var pct = total > 0 ? Math.round((value / total) * 100) : 0;
                            return ' ' + context.label + ': ' + pct + '%';
                        }",
                    ],
                ],
            ],
            'scales'              => (object) [],
            'maintainAspectRatio' => false,
        ];
    }
}