<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class SystemSetting extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'group',
        'key',
        'value',
        'value_type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];
}