<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Classroom::class => \App\Policies\ClassroomPolicy::class,
        \App\Models\ClassroomSession::class => \App\Policies\ClassroomSessionPolicy::class,
        \App\Models\Account::class => \App\Policies\AccountPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for account management
        Gate::define('manage-accounts', function ($user) {
            // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
            return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
        });

        Gate::define('view-accounts', function ($user) {
            // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
            return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
        });

        Gate::define('create-accounts', function ($user) {
            // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
            return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
        });

        Gate::define('update-accounts', function ($user) {
            // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
            return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
        });

        Gate::define('delete-accounts', function ($user) {
            // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
            return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
        });
    }
}
