<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiveItem extends Model
{
    protected $fillable = [
        'goods_receive_id',
        'purchase_order_item_id',
        'stock_item_id',
        'unit_id',
        'quantity_received',
        'unit_cost',
        'line_total',
        'expiry_date',
    ];

    protected $casts = [
        'quantity_received' => 'decimal:4',
        'unit_cost' => 'decimal:2',
        'line_total' => 'decimal:2',
        'expiry_date' => 'date',
    ];
}