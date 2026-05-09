<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    protected $fillable = [
        'customer_id',
        'order_id',
        'payment_id',
        'entry_type',
        'amount',
        'balance_after',
        'due_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'due_date' => 'date',
    ];
}