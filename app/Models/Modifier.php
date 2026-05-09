<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    protected $fillable = [
        'modifier_group_id',
        'name',
        'extra_price',
        'extra_cost',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
        'extra_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}