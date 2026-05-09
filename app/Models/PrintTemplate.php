<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class PrintTemplate extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'template_type',
        'name',
        'content',
        'settings',
        'is_default',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
    ];
}