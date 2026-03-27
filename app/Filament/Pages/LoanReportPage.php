<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Loan;
use App\Models\Group;
use App\Models\Member;
use App\Models\LoanType;

class LoanReportPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Loan Report';
    protected static ?int    $navigationSort  = 2;
    protected static string  $view            = 'filament.pages.reports.loan-report';

    public string $date_from  = '';
    public string $date_to    = '';
    public string $status     = '';
    public string $group_id   = '';
    public string $loan_type  = '';
    public string $search     = '';

    public array  $loans      = [];
    public array  $groups     = [];
    public array  $loanTypes  = [];

    public function mount(): void
    {
        $this->date_from  = now()->startOfYear()->format('Y-m-d');
        $this->date_to    = now()->format('Y-m-d');
        $user_g = auth()->user();
        $gq = Group::orderBy('name');
        if (!$user_g->hasRole('super_admin')) {
            $gq->where('assigned_officer', $user_g->id);
        }
        $this->groups = $gq->get(['id', 'name'])->toArray();
        $this->loanTypes  = LoanType::orderBy('loan_name')->get(['id', 'loan_name'])->toArray();
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
        $query = Loan::with(['member', 'loan_type']);

        if (!$user->hasRole('super_admin')) {
            $groupIds  = Group::where('assigned_officer', $user->id)->pluck('id');
            $memberIds = Member::whereIn('group_id', $groupIds)->pluck('id');
            $query->whereIn('member_id', $memberIds);
        }

        if ($this->date_from) {
            $query->where(function ($q) {
                $q->whereDate('loan_release_date', '>=', $this->date_from)
                  ->orWhere(function ($q2) {
                      $q2->whereNull('loan_release_date')
                         ->whereDate('created_at', '>=', $this->date_from);
                  });
            });
        }
        if ($this->date_to) {
            $query->where(function ($q) {
                $q->whereDate('loan_release_date', '<=', $this->date_to)
                  ->orWhere(function ($q2) {
                      $q2->whereNull('loan_release_date')
                         ->whereDate('created_at', '<=', $this->date_to);
                  });
            });
        }
        if ($this->status)    $query->where('loan_status', $this->status);
        if ($this->loan_type) $query->where('loan_type_id', $this->loan_type);
        if ($this->group_id) {
            $memberIds = Member::where('group_id', $this->group_id)->pluck('id');
            $query->whereIn('member_id', $memberIds);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('loan_number', 'like', '%'.$this->search.'%')
                  ->orWhereHas('member', fn($m) =>
                      $m->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name',  'like', '%'.$this->search.'%')
                        ->orWhere('id_number',  'like', '%'.$this->search.'%')
                  );
            });
        }

        $this->loans = $query->orderByDesc('created_at')->get()->map(function ($l) {
            $member = $l->member;
            return [
                'id'           => $l->id,
                'loan_number'  => $l->loan_number ?? 'LN-'.$l->id,
                'member_name'  => $member ? trim("{$member->first_name} {$member->last_name}") : '—',
                'id_number'    => $member?->id_number ?? '—',
                'group'        => Group::find($member?->group_id)?->name ?? '—',
                'loan_type'    => $l->loan_type?->loan_name ?? '—',
                'principal'    => (float)($l->principal_amount ?? $l->loan_amount ?? 0),
                'balance'      => (float)($l->balance ?? 0),
                'status'       => $l->loan_status ?? '—',
                'disbursed'    => $l->loan_release_date ?? $l->created_at?->format('Y-m-d'),
                'due'          => $l->clearance_date ?? $l->loan_due_date ?? '—',
                'installment'  => (float)($l->amount_per_installment ?? 0),
            ];
        })->toArray();
    }

    public function resetFilters(): void
    {
        $this->date_from = now()->startOfYear()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');
        $this->status    = '';
        $this->group_id  = '';
        $this->loan_type = '';
        $this->search    = '';
        $this->runReport();
    }

    public function getTotalLoansProperty(): int    { return count($this->loans); }
    public function getTotalDisbursedProperty(): float { return collect($this->loans)->sum('principal'); }
    public function getTotalBalanceProperty(): float   { return collect($this->loans)->sum('balance'); }
    public function getTotalRepaidProperty(): float    { return $this->totalDisbursed - $this->totalBalance; }
}