<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Payment extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'order_id',
        'invoice_id',
        'customer_id',
        'cash_shift_id',
        'payment_method_id',
        'payment_no',
        'payment_type',
        'amount',
        'received_amount',
        'change_amount',
        'reference_no',
        'status',
        'paid_by_user_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];
}