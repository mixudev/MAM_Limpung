<?php

namespace App\Policies;

use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleCategoryPolicy
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
     * Determine whether the user can view any categories.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view-article-categories') || $authUser->hasPermissionTo('view-articles');
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(User $authUser, ArticleCategory $category): bool
    {
        return $authUser->hasPermissionTo('view-article-categories') || $authUser->hasPermissionTo('view-articles');
    }

    /**
     * Determine whether the user can create categories.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create-article-categories');
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $authUser, ArticleCategory $category): bool
    {
        return $authUser->hasPermissionTo('edit-article-categories');
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $authUser, ArticleCategory $category): bool
    {
        return $authUser->hasPermissionTo('delete-article-categories');
    }
}
