<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableMove extends Model
{
    protected $fillable = [
        'from_table_id',
        'to_table_id',
        'moved_by',
        'reason',
        'moved_at',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];
}