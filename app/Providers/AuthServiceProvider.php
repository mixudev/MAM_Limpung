<?php

namespace App\Providers;

use App\Models\Prestasi;
use App\Models\User;
use App\Policies\Auth\UserPolicy;
use App\Policies\PrestasiPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Prestasi::class => PrestasiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // -----------------------------------------------------------------------
        //  Super Admin Gate Override
        //
        //  This ensures that super-admin bypasses ALL Gate checks (policies,
        //  @can directives, $this->authorize() calls) without needing to
        //  enumerate every policy method.
        //
        //  NOTE: This runs BEFORE individual policy methods, but AFTER the
        //  policy's own before() hook. The policy before() hook takes precedence.
        //
        //  Return null (not false) to allow other roles to pass through.
        // -----------------------------------------------------------------------
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }

            return null; // Fall through to individual policies
        });

        // -----------------------------------------------------------------------
        //  Custom Gates (for actions not covered by a specific policy)
        // -----------------------------------------------------------------------

        // Example: Only users with 'assign-roles' permission can manage roles
        Gate::define('manage-roles', fn (User $user) => $user->hasPermissionTo('assign-roles'));

        // Example: Access to admin reports section
        Gate::define('view-admin-reports', fn (User $user) => $user->hasPermissionTo('view-reports'));
    }
}
