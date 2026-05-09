<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Support\BranchContext;

class Role extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * Filter to roles relevant to the active branch (or null=system).
     * Used in admin UIs; NOT a global scope so we never recurse during auth checks.
     */
    public function scopeForCurrentBranch(Builder $q): Builder
    {
        $branchId = BranchContext::currentId();
        if ($branchId === null) {
            return $q;
        }
        return $q->where(fn ($qq) => $qq->whereNull('restaurant_id')->orWhere('restaurant_id', $branchId));
    }
}
