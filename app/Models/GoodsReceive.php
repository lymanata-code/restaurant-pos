<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceive extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'supplier_id',
        'grn_no',
        'status',
        'received_date',
        'total_amount',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];
}