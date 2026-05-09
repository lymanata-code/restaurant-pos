<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $table = 'staff';

    protected $fillable = [
        'restaurant_id',
        'staff_code',
        'name',
        'phone',
        'email',
        'address',
        'position',
        'hire_date',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];
}
