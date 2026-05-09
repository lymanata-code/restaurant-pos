<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    protected $fillable = [
        'stock_adjustment_id',
        'stock_item_id',
        'unit_id',
        'system_quantity',
        'counted_quantity',
        'difference_quantity',
        'notes',
    ];

    protected $casts = [
        'system_quantity' => 'decimal:4',
        'counted_quantity' => 'decimal:4',
        'difference_quantity' => 'decimal:4',
    ];
}