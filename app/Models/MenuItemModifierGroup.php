<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemModifierGroup extends Model
{
    protected $fillable = [
        'menu_item_id',
        'modifier_group_id',
    ];
}