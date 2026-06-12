<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
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
     * Determine whether the user can view any articles in the dashboard.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view-articles');
    }

    /**
     * Determine whether the user can view a specific article in the dashboard.
     */
    public function view(User $authUser, Article $article): bool
    {
        if (! $authUser->hasPermissionTo('view-articles')) {
            return false;
        }

        // Admins can view any article
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Authors (e.g., Guru) can only view their own articles
        return $authUser->id === $article->user_id;
    }

    /**
     * Determine whether the user can create articles.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create-articles');
    }

    /**
     * Determine whether the user can update the article.
     */
    public function update(User $authUser, Article $article): bool
    {
        if (! $authUser->hasPermissionTo('edit-articles')) {
            return false;
        }

        // Admins can update any article
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Authors cannot edit if the article has been rejected 2 or more times
        if ($article->rejection_count >= 2 && $authUser->id === $article->user_id) {
            return false;
        }

        // Authors can only update their own articles
        return $authUser->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     */
    public function delete(User $authUser, Article $article): bool
    {
        if (! $authUser->hasPermissionTo('delete-articles')) {
            return false;
        }

        // Admins can delete any article
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Authors cannot delete if the article has been rejected 2 or more times
        if ($article->rejection_count >= 2 && $authUser->id === $article->user_id) {
            return false;
        }

        // Authors can only delete their own articles
        return $authUser->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the article.
     */
    public function restore(User $authUser, Article $article): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the article.
     */
    public function forceDelete(User $authUser, Article $article): bool
    {
        return $authUser->hasRole('super-admin');
    }
}
