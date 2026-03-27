<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Saving;
use App\Models\Group;
use App\Models\Member;

class SavingsReportPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Savings Report';
    protected static ?int    $navigationSort  = 4;
    protected static string  $view            = 'filament.pages.reports.savings-report';

    public string $date_from = '';
    public string $date_to   = '';
    public string $group_id  = '';
    public string $search    = '';

    public array  $savings   = [];
    public array  $groups    = [];

    public function mount(): void
    {
        $this->date_from = now()->startOfMonth()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');
        $user_g = auth()->user();
        $gq = Group::orderBy('name');
        if (!$user_g->hasRole('super_admin')) {
            $gq->where('assigned_officer', $user_g->id);
        }
        $this->groups = $gq->get(['id', 'name'])->toArray();
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
        $query = Saving::with(['member', 'group']);

        if (!$user->hasRole('super_admin')) {
            $groupIds = Group::where('assigned_officer', $user->id)->pluck('id');
            $query->whereIn('group_id', $groupIds);
        }

        if ($this->date_from) $query->whereDate('contribution_date', '>=', $this->date_from);
        if ($this->date_to)   $query->whereDate('contribution_date', '<=', $this->date_to);
        if ($this->group_id)  $query->where('group_id', $this->group_id);

        if ($this->search) {
            $query->whereHas('member', fn($m) =>
                $m->where('first_name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name',  'like', '%'.$this->search.'%')
                  ->orWhere('id_number',  'like', '%'.$this->search.'%')
            );
        }

        $this->savings = $query->orderByDesc('contribution_date')->get()->map(function ($s) {
            return [
                'id'     => $s->id,
                'member' => $s->member ? trim("{$s->member->first_name} {$s->member->last_name}") : '—',
                'id_no'  => $s->member?->id_number ?? '—',
                'group'  => $s->group?->name ?? '—',
                'amount' => (float)$s->amount,
                'date'   => $s->contribution_date,
                'notes'  => $s->notes ?? '—',
            ];
        })->toArray();
    }

    public function resetFilters(): void
    {
        $this->date_from = now()->startOfMonth()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');
        $this->group_id  = '';
        $this->search    = '';
        $this->runReport();
    }

    public function getTotalSavingsProperty(): float  { return collect($this->savings)->sum('amount'); }
    public function getTotalRecordsProperty(): int    { return count($this->savings); }
    public function getGroupBreakdownProperty(): array {
        return collect($this->savings)
            ->groupBy('group')
            ->map(fn($g) => $g->sum('amount'))
            ->sortDesc()
            ->toArray();
    }
}