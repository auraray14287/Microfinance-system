<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Carbon\Carbon;

class MarkInactiveMembers extends Command
{
    protected $signature = 'members:mark-inactive';
    protected $description = 'Mark members as inactive if no savings updated in 60 days';

    public function handle(): void
    {
        $cutoff = Carbon::now()->subDays(60);

        $members = Member::where('status', 'active')->get();

        $count = 0;
        foreach ($members as $member) {
            $lastSaving = \App\Models\Saving::where('member_id', $member->id)
                ->orderBy('contribution_date', 'desc')
                ->first();

            // If no savings at all, use member creation date
            $lastDate = $lastSaving
                ? Carbon::parse($lastSaving->contribution_date)
                : Carbon::parse($member->created_at);

            if ($lastDate->lt($cutoff)) {
                $member->status = 'inactive';
                $member->save();
                $count++;
                $this->info("Marked inactive: {$member->first_name} {$member->last_name} (last saving: {$lastDate->toDateString()})");
            }
        }

        $this->info("Done. {$count} member(s) marked inactive.");
    }
}
