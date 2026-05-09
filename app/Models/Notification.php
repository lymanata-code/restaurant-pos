<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class Notification extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'title',
        'message',
        'notification_type',
        'channel',
        'reference_type',
        'reference_id',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}