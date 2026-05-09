<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Multi-branch helper.
 *
 * The "branch" concept is implemented as the `restaurants` row owning the data.
 * The active branch ID lives in the session under `current_branch_id`.
 *
 * - Super Admin: may pick "All Branches" (current_branch_id = null) and see everything.
 * - All other roles: are forced to their staff/user.restaurant_id and may not switch.
 */
class BranchContext
{
    public static function current(): ?Restaurant
    {
        $id = self::currentId();
        return $id ? Restaurant::find($id) : null;
    }

    public static function currentId(): ?int
    {
        $sessionId = session('current_branch_id');
        $user = auth()->user();
        if (!$user instanceof User) {
            return $sessionId ? (int) $sessionId : null;
        }

        if ($user->isSuperAdmin()) {
            // Super admin may switch freely (and may have NULL = all branches).
            return $sessionId !== null && $sessionId !== '' ? (int) $sessionId : null;
        }

        // Non-super-admins are pinned to their assigned branch.
        return $user->restaurant_id ? (int) $user->restaurant_id : null;
    }

    /** @return Collection<int, Restaurant> */
    public static function availableBranches(): Collection
    {
        $user = auth()->user();
        $query = Restaurant::query()->where('is_active', true)->orderBy('name');
        if ($user instanceof User && !$user->isSuperAdmin() && $user->restaurant_id) {
            $query->where('id', $user->restaurant_id);
        }
        return $query->get();
    }

    public static function setCurrent(?int $id): void
    {
        $user = auth()->user();
        if ($user instanceof User && !$user->isSuperAdmin()) {
            // ignored; non-super-admins are pinned
            return;
        }
        if ($id === null) {
            session()->forget('current_branch_id');
        } else {
            session(['current_branch_id' => $id]);
        }
    }
}
