<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class JournalEntry extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'journal_no',
        'journal_date',
        'reference_type',
        'reference_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'journal_date' => 'date',
    ];
}