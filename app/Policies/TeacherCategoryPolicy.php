<?php

namespace App\Policies;

use App\Models\User;

class TeacherCategoryPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-teacher-categories');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-teacher-categories');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-teacher-categories');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('edit-teacher-categories');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-teacher-categories');
    }
}
