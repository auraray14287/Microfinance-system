<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\Repayments;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class PrincipleReleased extends LineChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?string $maxHeight = '280px';
    protected static ?int    $sort      = 3;

    public function getHeading(): string { return 'Monthly Disbursements vs Repayments'; }

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate']   ?? null;
        $year      = now()->year;

        $disbursed = [];
        $repaid    = [];

        for ($month = 1; $month <= 12; $month++) {
            $disbursed[] = (float) $this->scopeLoans(Loan::query())
                ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('principal_amount');

            $repaid[] = (float) $this->scopeRepayments(Repayments::query())
                ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('payments');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Disbursed (KES)',
                    'data'            => $disbursed,
                    'borderColor'     => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.08)',
                    'tension'         => 0.4,
                    'fill'            => true,
                ],
                [
                    'label'           => 'Repaid (KES)',
                    'data'            => $repaid,
                    'borderColor'     => '#16a34a',
                    'backgroundColor' => 'rgba(22,163,74,0.08)',
                    'tension'         => 0.4,
                    'fill'            => true,
                ],
            ],
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];
    }
}