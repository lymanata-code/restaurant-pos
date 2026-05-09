<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class QrCode extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'table_id',
        'code',
        'url',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];
}