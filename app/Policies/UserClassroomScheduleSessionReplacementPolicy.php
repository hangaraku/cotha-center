<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserClassroomScheduleSessionReplacement;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserClassroomScheduleSessionReplacementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function view(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('view_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function update(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('update_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function delete(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('delete_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function forceDelete(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('force_delete_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function restore(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('restore_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserClassroomScheduleSessionReplacement  $userClassroomScheduleSessionReplacement
     * @return bool
     */
    public function replicate(User $user, UserClassroomScheduleSessionReplacement $userClassroomScheduleSessionReplacement): bool
    {
        return $user->can('replicate_user::classroom::schedule::session::replacement');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_user::classroom::schedule::session::replacement');
    }

}
