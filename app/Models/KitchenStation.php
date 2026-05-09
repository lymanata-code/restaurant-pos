<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class KitchenStation extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'printer_id',
        'name',
        'description',
        'auto_print_kot',
        'is_active',
    ];

    protected $casts = [
        'auto_print_kot' => 'boolean',
        'is_active' => 'boolean',
    ];
}