<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'promotion_id',
        'coupon_id',
        'discount_name',
        'discount_type',
        'discount_value',
        'discount_amount',
        'approved_by',
        'applied_by',
        'reason',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];
}