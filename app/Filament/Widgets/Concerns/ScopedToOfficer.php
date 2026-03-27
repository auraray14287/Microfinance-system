<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Group;
use App\Models\Member;
use App\Models\Loan;
use Illuminate\Support\Collection;

trait ScopedToOfficer
{
    /**
     * Returns group IDs the current user can see.
     * super_admin → all groups (returns null to mean "no restriction")
     * others      → only their assigned groups
     */
    protected function allowedGroupIds(): ?Collection
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            return null; // no restriction
        }
        return Group::where('assigned_officer', $user->id)->pluck('id');
    }

    protected function allowedMemberIds(): ?Collection
    {
        $groupIds = $this->allowedGroupIds();
        if ($groupIds === null) return null;
        return Member::whereIn('group_id', $groupIds)->pluck('id');
    }

    protected function allowedLoanIds(): ?Collection
    {
        $memberIds = $this->allowedMemberIds();
        if ($memberIds === null) return null;
        return Loan::whereIn('member_id', $memberIds)->pluck('id');
    }

    /**
     * Apply group scope to a Loan query builder.
     */
    protected function scopeLoans(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $memberIds = $this->allowedMemberIds();
        if ($memberIds !== null) {
            $query->whereIn('member_id', $memberIds);
        }
        return $query;
    }

    /**
     * Apply group scope to a Member query builder.
     */
    protected function scopeMembers(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $groupIds = $this->allowedGroupIds();
        if ($groupIds !== null) {
            $query->whereIn('group_id', $groupIds);
        }
        return $query;
    }

    /**
     * Apply group scope to a Saving query builder.
     */
    protected function scopeSavings(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $groupIds = $this->allowedGroupIds();
        if ($groupIds !== null) {
            $query->whereIn('group_id', $groupIds);
        }
        return $query;
    }

    /**
     * Apply group scope to a Repayments query builder.
     */
    protected function scopeRepayments(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $loanIds = $this->allowedLoanIds();
        if ($loanIds !== null) {
            $query->whereIn('loan_id', $loanIds);
        }
        return $query;
    }
}