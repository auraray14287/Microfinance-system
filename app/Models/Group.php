<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Group extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_number',
        'location',
        'contact',
        'assigned_officer',
        'chairperson',
        'secretary',
        'treasurer',
        'minimum_members',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Group $group) {
            if (empty($group->registration_number)) {
                $group->registration_number = static::generateRegistrationNumber();
            }
        });
    }

    public static function generateRegistrationNumber(?string $date = null): string
    {
        $date = $date ?? now()->format('Ymd');
        $prefix = 'GRP-' . $date . '-';

        $lastGroup = static::where('registration_number', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(registration_number, -3) AS UNSIGNED) DESC')
            ->first();

        if ($lastGroup) {
            $lastSeq = (int) substr($lastGroup->registration_number, -3);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'group_members');
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(User::class, 'supervisor_groups');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'assigned_officer');
    }
}
