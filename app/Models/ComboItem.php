<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    protected $fillable = [
        'combo_menu_item_id',
        'child_menu_item_id',
        'quantity',
        'is_required',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'is_required' => 'boolean',
    ];
}