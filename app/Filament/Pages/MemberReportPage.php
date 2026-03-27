<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Group;
use App\Models\User;
use App\Models\Saving;
use App\Models\Loan;

class MemberReportPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Member Report';
    protected static ?int    $navigationSort  = 1;
    protected static string  $view            = 'filament.pages.reports.member-report';

    // Filters
    public string  $search      = '';
    public string  $group_id    = '';
    public string  $status      = '';
    public string  $gender      = '';
    public string  $date_from   = '';
    public string  $date_to     = '';
    public string  $officer_id  = '';

    // Results
    public array   $members     = [];
    public bool    $searched    = false;

    public array   $groups      = [];
    public array   $officers    = [];
    public bool    $isSuperAdmin = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->isSuperAdmin = $user->hasRole('super_admin');

        // Load groups
        $gq = Group::orderBy('name');
        if (!$this->isSuperAdmin) {
            $gq->where('assigned_officer', $user->id);
        }
        $this->groups = $gq->get(['id', 'name'])->toArray();

        // Load ALL users who are assigned to at least one group (not just Officer role)
        if ($this->isSuperAdmin) {
            $officerIds = Group::distinct()->pluck('assigned_officer')->filter();
            $this->officers = User::whereIn('id', $officerIds)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        }

        $this->date_from = now()->startOfYear()->format('Y-m-d');
        $this->date_to   = now()->format('Y-m-d');
        $this->runReport();
    }

    public function updated(string $property): void
    {
        if (!in_array($property, ['members','loans','repayments','savings','defaulters','groups','loanTypes','searched','officers','isSuperAdmin'])) {
            if ($property === 'officer_id') {
                $this->group_id = '';
                $this->updateGroupsList();
            }
            $this->runReport();
        }
    }

    private function updateGroupsList(): void
    {
        $gq = Group::orderBy('name');

        if ($this->isSuperAdmin && $this->officer_id) {
            $gq->where('assigned_officer', $this->officer_id);
        } elseif (!$this->isSuperAdmin) {
            $gq->where('assigned_officer', auth()->id());
        }

        $this->groups = $gq->get(['id', 'name'])->toArray();
    }

    public function runReport(): void
    {
        $user  = auth()->user();
        $query = Member::with(['groups', 'loans', 'savings']);

        if (!$this->isSuperAdmin) {
            $groupIds = Group::where('assigned_officer', $user->id)->pluck('id');
            $query->whereIn('group_id', $groupIds);
        } elseif ($this->officer_id) {
            $officerGroupIds = Group::where('assigned_officer', $this->officer_id)->pluck('id');
            $query->where(function ($q) use ($officerGroupIds) {
                $q->where('assigned_officer', $this->officer_id)
                  ->orWhereIn('group_id', $officerGroupIds);
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name',  'like', '%'.$this->search.'%')
                  ->orWhere('last_name',  'like', '%'.$this->search.'%')
                  ->orWhere('id_number',  'like', '%'.$this->search.'%')
                  ->orWhere('mobile_no',  'like', '%'.$this->search.'%');
            });
        }
        if ($this->group_id)  $query->where('group_id',  $this->group_id);
        if ($this->status)    $query->where('status',    $this->status);
        if ($this->gender)    $query->where('gender',    $this->gender);
        if ($this->date_from) $query->whereDate('created_at', '>=', $this->date_from);
        if ($this->date_to)   $query->whereDate('created_at', '<=', $this->date_to);

        $results = $query->orderBy('first_name')->get();

        // Cache officer names to avoid repeated queries
        $officerCache = [];

        $this->members = $results->map(function ($m) use (&$officerCache) {
            $totalSavings    = $m->savings->sum('amount');
            $activeLoans     = $m->loans->whereIn('loan_status', ['approved', 'partially_paid', 'defaulted']);
            $totalLoanBal    = $activeLoans->sum('balance');

            // Get officer name (from member or from group)
            $officerId = $m->assigned_officer;
            if (!$officerId && $m->group_id) {
                $group = Group::find($m->group_id);
                $officerId = $group?->assigned_officer;
            }

            if ($officerId) {
                if (!isset($officerCache[$officerId])) {
                    $officerCache[$officerId] = User::find($officerId)?->name ?? '—';
                }
                $officerName = $officerCache[$officerId];
            } else {
                $officerName = '—';
            }

            return [
                'id'           => $m->id,
                'name'         => trim("{$m->first_name} {$m->middle_name} {$m->last_name}"),
                'id_number'    => $m->id_number,
                'mobile'       => $m->mobile_no,
                'gender'       => ucfirst($m->gender ?? ''),
                'status'       => $m->status,
                'group'        => $m->groups->pluck('name')->join(', ') ?: (Group::find($m->group_id)?->name ?? '—'),
                'officer'      => $officerName,
                'joined'       => $m->created_at?->format('d M Y'),
                'savings'      => $totalSavings,
                'loan_balance' => $totalLoanBal,
                'loan_count'   => $activeLoans->count(),
                'deposit'      => \App\Models\MemberDeposit::getBalance($m->id),
            ];
        })->toArray();

        $this->searched = true;
    }

    public function resetFilters(): void
    {
        $this->search     = '';
        $this->group_id   = '';
        $this->status     = '';
        $this->gender     = '';
        $this->officer_id = '';
        $this->date_from  = now()->startOfYear()->format('Y-m-d');
        $this->date_to    = now()->format('Y-m-d');
        $this->updateGroupsList();
        $this->runReport();
    }

    public function getTotalMembersProperty(): int   { return count($this->members); }
    public function getTotalSavingsProperty(): float  { return collect($this->members)->sum('savings'); }
    public function getTotalLoanBalProperty(): float  { return collect($this->members)->sum('loan_balance'); }
    public function getTotalDepositProperty(): float  { return collect($this->members)->sum('deposit'); }
    public function getActiveMembersProperty(): int   { return collect($this->members)->where('status', 'active')->count(); }
}
