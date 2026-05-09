<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_partner_id',
        'driver_user_id',
        'status',
        'delivery_fee',
        'tracking_no',
        'approved_at',
        'picked_up_at',
        'delivered_at',
        'delivery_note',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'approved_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
}