<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;

class Expense extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'account_id',
        'payment_method_id',
        'expense_no',
        'title',
        'amount',
        'expense_date',
        'reference_no',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
}