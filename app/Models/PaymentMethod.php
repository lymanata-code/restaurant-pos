<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class PaymentMethod extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'code',
        'name',
        'type',
        'requires_reference',
        'is_online',
        'is_active',
        'gateway_config',
    ];

    protected $casts = [
        'requires_reference' => 'boolean',
        'is_online' => 'boolean',
        'is_active' => 'boolean',
        'gateway_config' => 'array',
    ];
}