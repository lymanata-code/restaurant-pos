<?php

namespace App\Models\Concerns;

use App\Models\Restaurant;
use App\Support\BranchContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Apply to any Eloquent model whose table has a `restaurant_id` column.
 *
 * Adds:
 *  - `branch()` relation
 *  - global `BranchScope` that filters by the current branch
 *  - automatic `restaurant_id` fill on `creating`
 */
trait BelongsToBranch
{
    public static function bootBelongsToBranch(): void
    {
        static::addGlobalScope('branch', function (Builder $builder) {
            $branchId = BranchContext::currentId();
            if ($branchId !== null) {
                /** @var \Illuminate\Database\Eloquent\Model $model */
                $model = $builder->getModel();
                $builder->where($model->qualifyColumn('restaurant_id'), $branchId);
            }
        });

        static::creating(function ($model) {
            if (empty($model->restaurant_id)) {
                $model->restaurant_id = BranchContext::currentId();
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
