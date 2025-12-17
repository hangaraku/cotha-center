<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow super_admin, teacher, and supervisor to view accounts
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account): bool
    {
        // Allow super_admin, teacher, and supervisor to view accounts from their center
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!$user->hasRole('super_admin') && !$user->hasRole('Teacher') && !$user->hasRole('Supervisor')) {
            return false;
        }
        
        return $account->user->center_id === $user->center_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow super_admin, teacher, and supervisor to create accounts
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        return $user->hasRole('super_admin') || $user->hasRole('Teacher') || $user->hasRole('Supervisor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Account $account): bool
    {
        // Allow super_admin, teacher, and supervisor to update accounts from their center
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!$user->hasRole('super_admin') && !$user->hasRole('Teacher') && !$user->hasRole('Supervisor')) {
            return false;
        }
        
        return $account->user->center_id === $user->center_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Account $account): bool
    {
        // Allow super_admin, teacher, and supervisor to delete accounts from their center
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!$user->hasRole('super_admin') && !$user->hasRole('Teacher') && !$user->hasRole('Supervisor')) {
            return false;
        }
        
        return $account->user->center_id === $user->center_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Account $account): bool
    {
        // Allow super_admin, teacher, and supervisor to restore accounts from their center
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!$user->hasRole('super_admin') && !$user->hasRole('Teacher') && !$user->hasRole('Supervisor')) {
            return false;
        }
        
        return $account->user->center_id === $user->center_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Account $account): bool
    {
        // Allow super_admin, teacher, and supervisor to permanently delete accounts from their center
        // Note: hasRole() method exists from Spatie Permission trait, linter error is false positive
        if (!$user->hasRole('super_admin') && !$user->hasRole('Teacher') && !$user->hasRole('Supervisor')) {
            return false;
        }
        
        return $account->user->center_id === $user->center_id;
    }
}
