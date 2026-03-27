<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Group;
use App\Models\Saving;
use App\Models\Repayments;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Filament\Widgets\Concerns\ScopedToOfficer;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;
    use ScopedToOfficer;

    protected static ?int $sort = 1;

    public function getColumns(): int { return 3; }

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate']   ?? null;

        // 1. Total Members
        $memberQ         = $this->scopeMembers(Member::query());
        $totalMembers    = (clone $memberQ)->count();
        $activeMembers   = (clone $memberQ)->where('status', 'active')->count();
        $inactiveMembers = $totalMembers - $activeMembers;

        // 2. Total Disbursed vs Repaid
        $loanQ = $this->scopeLoans(Loan::query());

        $totalDisbursed = (clone $loanQ)
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate))
            ->sum('principal_amount');

        $totalRepaid = $this->scopeRepayments(Repayments::query())
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate))
            ->sum('payments');

        // 3. Savings this month
        $savingsThisMonth = $this->scopeSavings(Saving::query())
            ->whereMonth('contribution_date', now()->month)
            ->whereYear('contribution_date',  now()->year)
            ->sum('amount');

        // 4. Active Loans
        $activeLoans = (clone $loanQ)
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate,   fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate))
            ->whereIn('loan_status', ['approved', 'partially_paid'])
            ->count();

        $activeLoanBalance = (clone $loanQ)
            ->whereIn('loan_status', ['approved', 'partially_paid'])
            ->sum('balance');

        // 5. Defaulters
        $defaulterQ = (clone $loanQ)->where(function ($q) {
            $q->where('loan_status', 'defaulted')
              ->orWhere(function ($q2) {
                  $q2->whereIn('loan_status', ['approved', 'partially_paid'])
                     ->where('clearance_date', '<', now());
              });
        });
        $defaulterCount   = (clone $defaulterQ)->count();
        $defaulterBalance = (clone $defaulterQ)->sum('balance');

        // 6. Groups
        $allowedIds  = $this->allowedGroupIds();
        $groupQuery  = Group::query();
        if ($allowedIds !== null) $groupQuery->whereIn('id', $allowedIds);
        $totalGroups  = (clone $groupQuery)->count();
        $activeGroups = (clone $groupQuery)->where('status', 'active')->count();

        return [
            Stat::make('Total Members', number_format($totalMembers))
                ->description("{$activeMembers} active · {$inactiveMembers} inactive")
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Disbursed', 'KES ' . number_format($totalDisbursed, 0))
                ->description('Repaid: KES ' . number_format($totalRepaid, 0))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('info'),

            Stat::make('Savings This Month', 'KES ' . number_format($savingsThisMonth, 0))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Active Loans', number_format($activeLoans))
                ->description('Balance: KES ' . number_format($activeLoanBalance, 0))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info')
                ->url('/admin/loans'),

            Stat::make('Defaulters', number_format($defaulterCount))
                ->description('At risk: KES ' . number_format($defaulterBalance, 0))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($defaulterCount > 0 ? 'danger' : 'success'),

            Stat::make('Groups', number_format($totalGroups))
                ->description("{$activeGroups} active")
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),
        ];
    }
}