<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class Backup extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'created_by',
        'backup_no',
        'backup_type',
        'file_path',
        'size_bytes',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}