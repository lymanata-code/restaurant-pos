<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class DeliveryPartner extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'name',
        'contact_phone',
        'integration_config',
        'is_active',
    ];

    protected $casts = [
        'integration_config' => 'array',
        'is_active' => 'boolean',
    ];
}