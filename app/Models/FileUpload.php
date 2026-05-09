<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;

class FileUpload extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'restaurant_id',
        'uploaded_by',
        'uploadable_type',
        'uploadable_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];
}