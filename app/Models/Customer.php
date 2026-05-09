<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Customer extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'customer_code',
        'name',
        'phone',
        'email',
        'address',
        'membership_no',
        'points',
        'credit_limit',
        'outstanding_balance',
        'is_blacklisted',
        'notes',
        'status',
    ];

    protected $casts = [
        'points' => 'integer',
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'is_blacklisted' => 'boolean',
    ];
}