<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Member extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
    use HasFactory;

    protected $fillable = [
        'name', 'id_number', 'phone', 'email', 'address', 'status', 'last_contribution_date',
        'first_name', 'middle_name', 'last_name', 'gender', 'dob', 'marital_status',
        'physical_address', 'town', 'postal_code', 'county', 'sub_county', 'village',
        'nearest_market', 'mobile_no', 'kin_name', 'kin_mobile', 'kin_village',
        'kin_county', 'kin_town', 'kin_sub_location', 'kin_sub_county', 'kin_dob',
        'business_name', 'business_address', 'business_county', 'business_town',
        'business_sub_county', 'business_postal_code', 'guarantor1_name',
        'guarantor1_mobile', 'guarantor1_relationship', 'guarantor2_name',
        'guarantor2_mobile', 'guarantor2_relationship','assigned_officer','group_id',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members');
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function deposits()
    {
        return $this->hasMany(\App\Models\MemberDeposit::class);
    }

    public function depositBalance(): float
    {
        return (float) $this->deposits()->latest()->value('balance_after') ?? 0;
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}