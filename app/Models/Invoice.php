<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Invoice extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'order_id',
        'invoice_no',
        'receipt_no',
        'invoice_type',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'service_charge_amount',
        'tip_amount',
        'total_amount',
        'issued_at',
        'issued_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_charge_amount' => 'decimal:2',
        'tip_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];
}