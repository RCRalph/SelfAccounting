<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Extension;
use App\Models\User;

class ExtensionPolicy
{
    use HandlesAuthorization;

    public function hasExtension(User $user, Extension $extension)
    {
        return $user->extensions->contains($extension);
    }

    /**
     * Determine whether the user can toggle the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function toggle(User $user, Extension $extension)
    {
        return $user->extensions->contains($extension) ||
            in_array(strtolower($user->account_type), ["admin", "premium"]);
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function view(User $user, Extension $extension)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function update(User $user, Extension $extension)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function delete(User $user, Extension $extension)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function restore(User $user, Extension $extension)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Extension $extension
     * @return mixed
     */
    public function forceDelete(User $user, Extension $extension)
    {
        //
    }
}
