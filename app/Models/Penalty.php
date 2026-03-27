<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Penalty extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
    use HasFactory;

    protected $fillable = [
        'loan_id', 'amount', 'reason', 'penalty_date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}