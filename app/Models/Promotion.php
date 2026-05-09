<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Promotion extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'name',
        'promotion_type',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_spend',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'days_of_week',
        'requires_manager_approval',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'days_of_week' => 'array',
        'requires_manager_approval' => 'boolean',
        'is_active' => 'boolean',
    ];
}