<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'logo_path',
        'address',
        'phone',
        'email',
        'tax_number',
        'receipt_header',
        'receipt_footer',
        'refund_policy',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'restaurant_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'restaurant_id');
    }
}
