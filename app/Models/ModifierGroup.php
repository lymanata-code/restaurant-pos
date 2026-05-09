<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class ModifierGroup extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'name',
        'is_required',
        'min_select',
        'max_select',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];
}