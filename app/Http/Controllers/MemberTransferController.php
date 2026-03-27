<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Group;
use Illuminate\Http\Request;

class MemberTransferController extends Controller
{
    public function show(int $id)
    {
        $member = Member::with('groups')->findOrFail($id);

        $user  = auth()->user();
        $query = Group::where('status', 'active')->orderBy('name');

        if (!$user->hasRole('super_admin')) {
            $query->where('assigned_officer', $user->id);
        }

        $groups       = $query->get();
        $currentGroup = Group::find($member->group_id) ?? $member->groups->first();
        $currentGroupId = $currentGroup?->id ?? 0;

        // Build groups JSON in PHP so blade never calls @json() with arrow functions
        $groupsJson = json_encode(
            $groups->map(function ($g) use ($currentGroupId) {
                return [
                    'id'      => $g->id,
                    'name'    => $g->name,
                    'loc'     => $g->location ?? '',
                    'reg'     => $g->registration_number ?? '',
                    'current' => ($g->id === $currentGroupId),
                ];
            })->values()->all()
        );

        return view('filament.pages.member-transfer', [
            'member'       => $member,
            'groups'       => $groups,
            'currentGroup' => $currentGroup,
            'groupsJson'   => $groupsJson,
        ]);
    }

    public function transfer(Request $request, int $id)
    {
        $request->validate([
            'new_group_id' => 'required|exists:groups,id',
            'reason'       => 'required|string|min:5|max:500',
        ]);

        $member     = Member::with('groups')->findOrFail($id);
        $newGroupId = (int) $request->new_group_id;
        $oldGroupId = $member->group_id ?? $member->groups->first()?->id;

        if ($newGroupId === $oldGroupId) {
            return back()
                ->withErrors(['new_group_id' => 'Member is already in this group.'])
                ->withInput();
        }

        $member->group_id = $newGroupId;
        $member->save();

        try { $member->groups()->sync([$newGroupId]); } catch (\Exception $e) {}

        try {
            activity()
                ->performedOn($member)
                ->causedBy(auth()->user())
                ->withProperties([
                    'from_group_id' => $oldGroupId,
                    'to_group_id'   => $newGroupId,
                    'reason'        => $request->reason,
                ])
                ->log('Member transferred to new group');
        } catch (\Exception $e) {}

        $newGroupName = Group::find($newGroupId)?->name ?? 'new group';

        return redirect()
            ->route('filament.admin.pages.member-profile')
            ->with('transfer_success', "Member successfully transferred to {$newGroupName}.")
            ->with('prefill_id', $member->id_number);
    }
}