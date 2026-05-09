<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class StockItem extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'inventory_category_id',
        'unit_id',
        'item_code',
        'name',
        'purchase_price',
        'quantity_on_hand',
        'reorder_level',
        'reorder_quantity',
        'expiry_date',
        'is_ingredient',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'quantity_on_hand' => 'decimal:4',
        'reorder_level' => 'decimal:4',
        'reorder_quantity' => 'decimal:4',
        'expiry_date' => 'date',
        'is_ingredient' => 'boolean',
        'is_active' => 'boolean',
    ];
}