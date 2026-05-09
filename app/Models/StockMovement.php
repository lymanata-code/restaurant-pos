<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'stock_item_id',
        'unit_id',
        'reference_type',
        'reference_id',
        'movement_type',
        'quantity',
        'unit_cost',
        'total_cost',
        'balance_after',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'balance_after' => 'decimal:4',
    ];
}