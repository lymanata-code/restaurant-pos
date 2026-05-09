<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_id',
        'payment_id',
        'refund_no',
        'amount',
        'reason',
        'status',
        'requested_by',
        'approved_by',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];
}