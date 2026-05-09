<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class TaxRate extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'name',
        'rate',
        'type',
        'is_inclusive',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_inclusive' => 'boolean',
        'is_active' => 'boolean',
    ];
}