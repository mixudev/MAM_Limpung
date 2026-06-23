<?php

namespace App\Policies;

use App\Models\User;

class TeacherPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-teachers');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-teachers');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-teachers');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('edit-teachers');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-teachers');
    }
}
