<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'menu_item_id',
        'stock_item_id',
        'unit_id',
        'quantity_required',
        'wastage_percent',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
        'wastage_percent' => 'decimal:4',
    ];
}