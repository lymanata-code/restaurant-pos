<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Supplier extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'supplier_code',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'outstanding_balance',
        'status',
    ];

    protected $casts = [
        'outstanding_balance' => 'decimal:2',
    ];
}