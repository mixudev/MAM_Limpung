<?php

namespace App\Policies;

use App\Models\User;

class StudentPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-students');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-students');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-students');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('edit-students');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-students');
    }
}
