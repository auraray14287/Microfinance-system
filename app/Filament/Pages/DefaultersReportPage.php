<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Loan;
use App\Models\Group;
use App\Models\Member;
use Carbon\Carbon;

class DefaultersReportPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Defaulters Report';
    protected static ?int    $navigationSort  = 5;
    protected static string  $view            = 'filament.pages.reports.defaulters-report';

    public string $group_id      = '';
    public string $overdue_type  = ''; // 'defaulted' | 'overdue' | ''
    public string $date_from     = '';
    public string $date_to       = '';
    public string $search        = '';

    public array  $defaulters    = [];
    public array  $groups        = [];

    public function mount(): void
    {
        $user_g = auth()->user();
        $gq = Group::orderBy('name');
        if (!$user_g->hasRole('super_admin')) {
            $gq->where('assigned_officer', $user_g->id);
        }
        $this->groups = $gq->get(['id', 'name'])->toArray();
        $this->date_from = now()->startOfYear()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');
        $this->runReport();
    }

    // Auto-rerun whenever any filter property changes (live search, dropdowns, dates)
    public function updated(string $property): void
    {
        // Skip re-running for non-filter internal properties
        if (!in_array($property, ['members','loans','repayments','savings','defaulters','groups','loanTypes','searched'])) {
            $this->runReport();
        }
    }

    public function runReport(): void
    {
        $user  = auth()->user();
        $overdueThreshold = now()->subDays(7)->toDateString();
        $query = Loan::with(['member', 'loan_type'])
            ->where(function ($q) use ($overdueThreshold) {
                $q->where('loan_status', 'defaulted')
                  ->orWhere(function ($q2) use ($overdueThreshold) {
                      // Overdue = approved/partially_paid with next_payment_date 7+ days past
                      $q2->whereIn('loan_status', ['partially_paid', 'approved'])
                         ->where('next_payment_date', '<', $overdueThreshold)
                         ->where('balance', '>', 0);
                  });
            });

        if (!$user->hasRole('super_admin')) {
            $groupIds  = Group::where('assigned_officer', $user->id)->pluck('id');
            $memberIds = Member::whereIn('group_id', $groupIds)->pluck('id');
            $query->whereIn('member_id', $memberIds);
        }

        if ($this->overdue_type === 'defaulted') {
            $query->where('loan_status', 'defaulted');
        } elseif ($this->overdue_type === 'overdue') {
            $overdueThreshold = now()->subDays(7)->toDateString();
            $query->whereIn('loan_status', ['partially_paid', 'approved'])
                  ->where('next_payment_date', '<', $overdueThreshold)
                  ->where('balance', '>', 0);
        }

        if ($this->group_id) {
            $memberIds = Member::where('group_id', $this->group_id)->pluck('id');
            $query->whereIn('member_id', $memberIds);
        }

        if ($this->date_from) $query->whereDate('created_at', '>=', $this->date_from);
        if ($this->date_to)   $query->whereDate('created_at', '<=', $this->date_to);

        if ($this->search) {
            $query->whereHas('member', fn($m) =>
                $m->where('first_name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name',  'like', '%'.$this->search.'%')
                  ->orWhere('id_number',  'like', '%'.$this->search.'%')
            );
        }

        $this->defaulters = $query->orderByDesc('balance')->get()->map(function ($l) {
            $member  = $l->member;
            $nextPayment = $l->next_payment_date ?? null;
            $daysOverdue = $nextPayment ? max(0, (int)Carbon::parse($nextPayment)->diffInDays(now(), false)) : 0;

            // Determine type
            if ($l->loan_status === 'defaulted') {
                $type = 'Defaulted';
            } elseif ($nextPayment && Carbon::parse($nextPayment)->isPast() && $daysOverdue >= 7) {
                $type = 'Overdue';
            } else {
                $type = 'At Risk';
            }

            return [
                'id'           => $l->id,
                'loan_number'  => $l->loan_number ?? 'LN-'.$l->id,
                'member'       => $member ? trim("{$member->first_name} {$member->last_name}") : '—',
                'id_number'    => $member?->id_number ?? '—',
                'mobile'       => $member?->mobile_no ?? '—',
                'group'        => Group::find($member?->group_id)?->name ?? '—',
                'loan_type'    => $l->loan_type?->name ?? '—',
                'principal'    => (float)($l->principal_amount ?? $l->loan_amount ?? 0),
                'balance'      => (float)($l->balance ?? 0),
                'due_date'     => $nextPayment ? Carbon::parse($nextPayment)->format('d M Y') : '—',
                'days_overdue' => $daysOverdue,
                'type'         => $type,
                'status'       => $l->loan_status,
            ];
        })->toArray();
    }

    public function resetFilters(): void
    {
        $this->group_id     = '';
        $this->overdue_type = '';
        $this->date_from    = now()->startOfYear()->format('Y-m-d');
        $this->date_to      = now()->format('Y-m-d');
        $this->search       = '';
        $this->runReport();
    }

    public function getTotalDefaultersProperty(): int  { return count($this->defaulters); }
    public function getTotalAtRiskProperty(): float    { return collect($this->defaulters)->sum('balance'); }
    public function getDefaultedCountProperty(): int   { return collect($this->defaulters)->where('type', 'Defaulted')->count(); }
    public function getOverdueCountProperty(): int     { return collect($this->defaulters)->where('type', 'Overdue')->count(); }
}