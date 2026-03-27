<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Repayments;
use App\Models\Group;
use App\Models\Member;
use App\Models\Loan;

class RepaymentsReportPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Repayments Report';
    protected static ?int    $navigationSort  = 3;
    protected static string  $view            = 'filament.pages.reports.repayments-report';

    public string $date_from       = '';
    public string $date_to         = '';
    public string $group_id        = '';
    public string $payment_method  = '';
    public string $search          = '';

    public array  $repayments      = [];
    public array  $groups          = [];

    public function mount(): void
    {
        $this->date_from = now()->startOfMonth()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');

        // Scope the groups dropdown to officer's assigned groups only
        $user = auth()->user();
        $gq   = Group::orderBy('name');
        if (!$user->hasRole('super_admin')) {
            $gq->where('assigned_officer', $user->id);
        }
        $this->groups = $gq->get(['id', 'name'])->toArray();

        $this->runReport();
    }

    // Auto-rerun whenever any filter property changes
    public function updated(string $property): void
    {
        if (!in_array($property, ['repayments', 'groups', 'searched'])) {
            $this->runReport();
        }
    }

    public function runReport(): void
    {
        $user  = auth()->user();

        // Build the set of loan IDs this user is allowed to see
        if (!$user->hasRole('super_admin')) {
            $allowedGroupIds  = Group::where('assigned_officer', $user->id)->pluck('id');
            $allowedMemberIds = Member::whereIn('group_id', $allowedGroupIds)->pluck('id');
            $allowedLoanIds   = Loan::whereIn('member_id', $allowedMemberIds)->pluck('id');
        }

        $query = Repayments::with(['loan.member']);

        // Apply role scope
        if (!$user->hasRole('super_admin')) {
            $query->whereIn('loan_id', $allowedLoanIds);
        }

        if ($this->date_from) $query->whereDate('created_at', '>=', $this->date_from);
        if ($this->date_to)   $query->whereDate('created_at', '<=', $this->date_to);
        if ($this->payment_method) $query->where('payments_method', $this->payment_method);

        // Group filter — only allowed if within officer's scope
        if ($this->group_id) {
            $groupMemberIds = Member::where('group_id', $this->group_id)->pluck('id');
            $groupLoanIds   = Loan::whereIn('member_id', $groupMemberIds)->pluck('id');
            // If officer, intersect with their allowed loans
            if (!$user->hasRole('super_admin')) {
                $groupLoanIds = $groupLoanIds->intersect($allowedLoanIds)->values();
            }
            $query->whereIn('loan_id', $groupLoanIds);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference_number', 'like', '%'.$this->search.'%')
                  ->orWhere('loan_number',     'like', '%'.$this->search.'%')
                  ->orWhereHas('loan.member', function ($m) {
                      $m->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name',  'like', '%'.$this->search.'%')
                        ->orWhere('id_number',  'like', '%'.$this->search.'%');
                  });
            });
        }

        $this->repayments = $query->orderByDesc('created_at')->get()->map(function ($r) {
            $member = $r->loan?->member;
            return [
                'id'        => $r->id,
                'ref'       => $r->reference_number ?? '—',
                'loan_no'   => $r->loan_number ?? 'LN-'.$r->loan_id,
                'member'    => $member ? trim("{$member->first_name} {$member->last_name}") : '—',
                'id_number' => $member?->id_number ?? '—',
                'group'     => Group::find($member?->group_id)?->name ?? '—',
                'amount'    => (float)$r->payments,
                'balance'   => (float)$r->balance,
                'method'    => $r->payments_method ?? '—',
                'date'      => $r->created_at?->format('d M Y'),
            ];
        })->toArray();
    }

    public function resetFilters(): void
    {
        $this->date_from      = now()->startOfMonth()->format('Y-m-d');
        $this->date_to        = now()->format('Y-m-d');
        $this->group_id       = '';
        $this->payment_method = '';
        $this->search         = '';
        $this->runReport();
    }

    public function getTotalRepaymentsProperty(): int  { return count($this->repayments); }
    public function getTotalCollectedProperty(): float  { return collect($this->repayments)->sum('amount'); }
    public function getMethodBreakdownProperty(): array {
        return collect($this->repayments)
            ->groupBy('method')
            ->map(fn($g) => $g->sum('amount'))
            ->toArray();
    }
}