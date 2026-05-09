<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'promotion_id',
        'code',
        'usage_limit',
        'used_count',
        'usage_limit_per_customer',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}