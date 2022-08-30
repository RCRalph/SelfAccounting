<?php

namespace App\Policies;

use App\Backup;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BackupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Backup  $backup
     * @return mixed
     */
    public function view(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (!$user->backup) {
            return false;
        }

        return !$user->backup->last_backup ||
            now()->subDays(1)->gte($user->backup->last_backup);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Backup  $backup
     * @return mixed
     */
    public function update(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Backup  $backup
     * @return mixed
     */
    public function delete(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Backup  $backup
     * @return mixed
     */
    public function restore(User $user)
    {
        if (!$user->backup) {
            return false;
        }

        return !$user->backup->last_restoration ||
            now()->subDays(1)->gte($user->backup->last_restoration) &&
            now()->subDays(7)->gte($user->created_at);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Backup  $backup
     * @return mixed
     */
    public function forceDelete(User $user, Backup $backup)
    {
        //
    }
}
