<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Backup;
use App\Models\User;

class BackupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Backup  $backup
     * @return mixed
     */
    public function view(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (!$user->backup) {
            return false;
        }

        return env("APP_DEBUG") || !$user->backup->last_backup ||
            now()->subDays(1)->gte($user->backup->last_backup);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Backup  $backup
     * @return mixed
     */
    public function update(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Backup  $backup
     * @return mixed
     */
    public function delete(User $user, Backup $backup)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Backup  $backup
     * @return mixed
     */
    public function restore(User $user)
    {
        if (!$user->backup) {
            return false;
        }

        return env("APP_DEBUG") || !$user->backup->last_restoration ||
            now()->subDays(1)->gte($user->backup->last_restoration) &&
            now()->subDays(30)->gte($user->created_at);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Backup  $backup
     * @return mixed
     */
    public function forceDelete(User $user, Backup $backup)
    {
        //
    }
}
