<?php

namespace App\Policies;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrestasiPolicy
{
    use HandlesAuthorization;

    /**
     * Before hook: Super Admin can do anything.
     */
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any achievements in the dashboard.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view-achievements');
    }

    /**
     * Determine whether the user can view a specific achievement in the dashboard.
     */
    public function view(User $authUser, Prestasi $prestasi): bool
    {
        return $authUser->hasPermissionTo('view-achievements');
    }

    /**
     * Determine whether the user can create achievements.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create-achievements');
    }

    /**
     * Determine whether the user can update the achievement.
     */
    public function update(User $authUser, Prestasi $prestasi): bool
    {
        return $authUser->hasPermissionTo('edit-achievements');
    }

    /**
     * Determine whether the user can delete the achievement.
     */
    public function delete(User $authUser, Prestasi $prestasi): bool
    {
        return $authUser->hasPermissionTo('delete-achievements');
    }

    /**
     * Determine whether the user can restore the achievement.
     */
    public function restore(User $authUser, Prestasi $prestasi): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the achievement.
     */
    public function forceDelete(User $authUser, Prestasi $prestasi): bool
    {
        return $authUser->hasRole('super-admin');
    }
}
