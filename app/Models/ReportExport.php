<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class ReportExport extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'requested_by',
        'report_type',
        'format',
        'filters',
        'file_path',
        'status',
        'generated_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'generated_at' => 'datetime',
    ];
}