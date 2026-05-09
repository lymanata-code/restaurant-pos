<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModifier extends Model
{
    protected $fillable = [
        'order_item_id',
        'modifier_id',
        'modifier_group_name',
        'modifier_name',
        'extra_price',
        'extra_cost',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
        'extra_cost' => 'decimal:2',
    ];
}