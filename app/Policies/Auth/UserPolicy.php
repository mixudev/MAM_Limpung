<?php

namespace App\Policies\Auth;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * UserPolicy
 *
 * Defines authorization logic for User resource actions.
 * Called via $this->authorize() in controllers or @can in Blade.
 *
 * Before hook: Super Admin bypasses all checks.
 *
 * Best practice:
 *   - Controllers call $this->authorize('update', $targetUser)
 *   - Blade uses @can('update', $targetUser) / @cannot
 *   - Never hardcode role names in business logic — use permissions
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Before hook: Super Admin can do anything.
     * Return null to fall through to individual methods for other roles.
     */
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->hasRole('super-admin')) {
            return true;
        }

        return null; // Let individual methods decide
    }

    /**
     * Can view a list of users?
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view-users');
    }

    /**
     * Can view a specific user?
     */
    public function view(User $authUser, User $targetUser): bool
    {
        // Users can always view themselves
        if ($authUser->id === $targetUser->id) {
            return true;
        }

        return $authUser->hasPermissionTo('view-users');
    }

    /**
     * Can create a new user?
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create-users');
    }

    /**
     * Can update a user?
     * Admins cannot update Super Admins.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        if (! $authUser->hasPermissionTo('edit-users')) {
            return false;
        }

        // Prevent privilege escalation: cannot modify higher-level users
        $authLevel = $authUser->roles->max('level') ?? 0;
        $targetLevel = $targetUser->roles->max('level') ?? 0;

        return $authLevel > $targetLevel || $authUser->id === $targetUser->id;
    }

    /**
     * Can delete a user?
     * Cannot delete yourself.
     */
    public function delete(User $authUser, User $targetUser): Response
    {
        if ($authUser->id === $targetUser->id) {
            return Response::deny('Anda tidak dapat menghapus akun sendiri.');
        }

        if (! $authUser->hasPermissionTo('delete-users')) {
            return Response::deny('Anda tidak memiliki izin untuk menghapus pengguna.');
        }

        // Prevent privilege escalation
        $authLevel = $authUser->roles->max('level') ?? 0;
        $targetLevel = $targetUser->roles->max('level') ?? 0;

        if ($authLevel <= $targetLevel) {
            return Response::deny('Anda tidak dapat menghapus pengguna dengan level yang sama atau lebih tinggi.');
        }

        return Response::allow();
    }

    /**
     * Can restore a soft-deleted user?
     */
    public function restore(User $authUser, User $targetUser): bool
    {
        return $authUser->hasPermissionTo('restore-users');
    }

    /**
     * Can permanently delete a user?
     */
    public function forceDelete(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('super-admin');
    }

    /**
     * Can assign roles to a user?
     */
    public function assignRole(User $authUser, User $targetUser): bool
    {
        return $authUser->hasPermissionTo('assign-roles');
    }
}
