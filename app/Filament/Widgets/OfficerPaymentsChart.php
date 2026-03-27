<?php

namespace App\Filament\Widgets;

use App\Models\Group;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Repayments;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;

class OfficerPaymentsChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;

    protected static ?int    $sort      = 4;
    protected static ?string $maxHeight = '400px';

    public ?string $filter = null;

    public function getHeading(): string
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin') && $this->filter) {
            $officer = User::find((int) $this->filter);
            return 'Expected vs Received — ' . ($officer?->name ?? 'Officer');
        }
        if (!$user->hasRole('super_admin')) {
            return 'Expected vs Received — My Portfolio';
        }
        return 'Expected vs Received Payments (All Officers)';
    }

    protected function getFilters(): ?array
    {
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            return null;
        }
        $officerIds = Group::whereNotNull('assigned_officer')->distinct()->pluck('assigned_officer');
        $officers   = User::whereIn('id', $officerIds)->orderBy('name')->pluck('name', 'id')->toArray();
        return array_merge(['' => 'All Officers'], $officers);
    }

    protected function getData(): array
    {
        $user        = auth()->user();
        $currentYear = (int) Carbon::now()->year;

        if ($user->hasRole('super_admin') && !empty($this->filter)) {
            $groupIds  = Group::where('assigned_officer', (int) $this->filter)->pluck('id');
            $memberIds = Member::whereIn('group_id', $groupIds)->pluck('id');
        } elseif ($user->hasRole('super_admin')) {
            $memberIds = null;
        } else {
            $groupIds  = Group::where('assigned_officer', $user->id)->pluck('id');
            $memberIds = Member::whereIn('group_id', $groupIds)->pluck('id');
        }

        $loanQuery = Loan::whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
        if ($memberIds !== null) {
            $loanQuery->whereIn('member_id', $memberIds);
        }
        $activeLoans = $loanQuery->get([
            'id',
            'amount_per_installment',
            'loan_release_date',
            'next_payment_date',
            'duration_period',
            'created_at',
        ]);
        $loanIds = $activeLoans->pluck('id');

        $expectedData = [];
        $receivedData = [];
        $labels       = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[]   = Carbon::create($currentYear, $month)->format('M');
            $monthStart = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $monthEnd   = Carbon::create($currentYear, $month, 1)->endOfMonth();

            $expectedMonthly = 0.0;

            foreach ($activeLoans as $loan) {
                $installment = (float)($loan->amount_per_installment ?? 0);
                if ($installment <= 0) continue;

                $isWeekly  = str_contains(strtolower($loan->duration_period ?? ''), 'week');
                $stepWeeks = $isWeekly ? 1 : 3;

                $anchor = $loan->next_payment_date
                    ? Carbon::parse($loan->next_payment_date)
                    : Carbon::parse($loan->created_at)->addWeeks($stepWeeks);

                $yearStart = Carbon::create($currentYear, 1, 1);
                $cursor    = $anchor->copy();

                while ($cursor->gt($yearStart)) {
                    $cursor->subWeeks($stepWeeks);
                }
                while ($cursor->lt($yearStart)) {
                    $cursor->addWeeks($stepWeeks);
                }

                $count = 0;
                $probe = $cursor->copy();
                for ($i = 0; $i < 60; $i++) {
                    if ($probe->gt($monthEnd)) break;
                    if ($probe->gte($monthStart) && $probe->lte($monthEnd)) {
                        $count++;
                    }
                    $probe->addWeeks($stepWeeks);
                }

                $expectedMonthly += $installment * $count;
            }

            $expectedData[] = round($expectedMonthly, 2);

            $received = (float) Repayments::whereIn('loan_id', $loanIds)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('payments');
            $receivedData[] = round($received, 2);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Expected (KES)',
                    'type'            => 'bar',
                    'data'            => $expectedData,
                    'backgroundColor' => 'rgba(59,130,246,0.55)',
                    'borderColor'     => 'rgba(59,130,246,1)',
                    'borderWidth'     => 1,
                    'borderRadius'    => 4,
                    'order'           => 2,
                ],
                [
                    'label'                => 'Received (KES)',
                    'type'                 => 'line',
                    'data'                 => $receivedData,
                    'backgroundColor'      => 'rgba(34,197,94,0.15)',
                    'borderColor'          => 'rgba(34,197,94,1)',
                    'borderWidth'          => 2,
                    'pointBackgroundColor' => 'rgba(34,197,94,1)',
                    'pointRadius'          => 4,
                    'fill'                 => false,
                    'tension'              => 0.4,
                    'order'                => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string { return 'bar'; }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['color' => '#6b7280'],
                    'grid'        => ['color' => 'rgba(0,0,0,0.05)'],
                ],
                'x' => [
                    'ticks' => ['color' => '#6b7280'],
                    'grid'  => ['display' => false],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
