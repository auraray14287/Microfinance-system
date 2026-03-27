<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'amount', 'type', 'reference', 'contact',
        'loan_id', 'notes', 'balance_after', 'created_by'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper: get current deposit balance for a member
    public static function getBalance(int $memberId): float
    {
        return (float) static::where('member_id', $memberId)->latest()->value('balance_after') ?? 0;
    }

    // Helper: credit deposit account
    public static function credit(int $memberId, float $amount, string $notes = '', ?int $loanId = null, ?int $createdBy = null, ?string $reference = null, ?string $contact = null): self
    {
        $currentBalance = static::getBalance($memberId);
        $newBalance = $currentBalance + $amount;
        return static::create([
            'member_id'    => $memberId,
            'amount'       => $amount,
            'type'         => 'credit',
            'notes'        => $notes,
            'loan_id'      => $loanId,
            'reference'    => $reference,
            'contact'      => $contact,
            'balance_after'=> $newBalance,
            'created_by'   => $createdBy ?? auth()->id(),
        ]);
    }

    // Helper: debit deposit account
    public static function debit(int $memberId, float $amount, string $notes = '', ?int $loanId = null, ?int $createdBy = null): self
    {
        $currentBalance = static::getBalance($memberId);
        $newBalance = max(0, $currentBalance - $amount);
        return static::create([
            'member_id'    => $memberId,
            'amount'       => $amount,
            'type'         => 'debit',
            'notes'        => $notes,
            'loan_id'      => $loanId,
            'balance_after'=> $newBalance,
            'created_by'   => $createdBy ?? auth()->id(),
        ]);
    }
}
