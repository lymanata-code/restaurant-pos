<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenTicketItem extends Model
{
    protected $fillable = [
        'kitchen_ticket_id',
        'order_item_id',
    ];
}