<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenTicket extends Model
{
    protected $fillable = [
        'kot_no',
        'order_id',
        'kitchen_station_id',
        'status',
        'printed_at',
        'ready_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'ready_at' => 'datetime',
    ];
}