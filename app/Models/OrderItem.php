<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'menu_item_price_id',
        'kitchen_station_id',
        'item_name',
        'size_name',
        'quantity',
        'unit_price',
        'unit_cost',
        'modifier_total',
        'discount_amount',
        'tax_amount',
        'line_total',
        'status',
        'special_note',
        'sent_to_kitchen_at',
        'ready_at',
        'served_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'modifier_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'sent_to_kitchen_at' => 'datetime',
        'ready_at' => 'datetime',
        'served_at' => 'datetime',
    ];
}