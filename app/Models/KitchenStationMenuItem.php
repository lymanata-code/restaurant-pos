<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenStationMenuItem extends Model
{
    protected $fillable = [
        'kitchen_station_id',
        'menu_item_id',
    ];
}