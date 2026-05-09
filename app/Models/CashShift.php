<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class CashShift extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'cashier_id',
        'shift_no',
        'start_cash',
        'expected_cash',
        'counted_cash',
        'short_over_amount',
        'status',
        'opened_at',
        'closed_at',
        'closed_by',
        'notes',
    ];

    protected $casts = [
        'start_cash' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'counted_cash' => 'decimal:2',
        'short_over_amount' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
}