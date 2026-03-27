<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'loan_id', 'product_id', 'quantity', 'unit_price', 'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
