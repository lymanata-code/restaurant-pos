<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class CodeSequence extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'sequence_type',
        'prefix',
        'date_format',
        'next_number',
        'padding',
        'suffix',
        'reset_daily',
    ];

    protected $casts = [
        'reset_daily' => 'boolean',
        'next_number' => 'integer',
        'padding' => 'integer',
    ];
}