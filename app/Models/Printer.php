<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class Printer extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'name',
        'printer_type',
        'ip_address',
        'port',
        'paper_size',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}