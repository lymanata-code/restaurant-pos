<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class StockAdjustment extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'adjustment_no',
        'restaurant_id',
        'status',
        'reason',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];
}