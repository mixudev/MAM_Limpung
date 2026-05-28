<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GaleriPolicy
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
     * Determine whether the user can view any galleries in the dashboard.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view-galeri');
    }

    /**
     * Determine whether the user can view a specific gallery in the dashboard.
     */
    public function view(User $authUser, Galeri $galeri): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        return $authUser->id === $galeri->user_id && $authUser->hasPermissionTo('view-galeri');
    }

    /**
     * Determine whether the user can create galleries.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create-galeri');
    }

    /**
     * Determine whether the user can update the gallery.
     */
    public function update(User $authUser, Galeri $galeri): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Students and teachers can only update their own gallery if it is still pending approval
        return $authUser->id === $galeri->user_id && $galeri->status === 'pending' && $authUser->hasPermissionTo('edit-galeri');
    }

    /**
     * Determine whether the user can delete the gallery.
     */
    public function delete(User $authUser, Galeri $galeri): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Students and teachers can only delete their own gallery if it is still pending approval
        return $authUser->id === $galeri->user_id && $galeri->status === 'pending' && $authUser->hasPermissionTo('delete-galeri');
    }

    /**
     * Determine whether the user can approve/reject the gallery.
     */
    public function approve(User $authUser): bool
    {
        return $authUser->hasPermissionTo('approve-galeri');
    }

    /**
     * Determine whether the user can restore the gallery.
     */
    public function restore(User $authUser, Galeri $galeri): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the gallery.
     */
    public function forceDelete(User $authUser, Galeri $galeri): bool
    {
        return $authUser->hasRole('super-admin');
    }
}
