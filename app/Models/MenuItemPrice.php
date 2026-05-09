<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemPrice extends Model
{
    protected $fillable = [
        'menu_item_id',
        'size_name',
        'unit_name',
        'price',
        'cost_price',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}