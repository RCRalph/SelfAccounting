<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Budget;
use App\Models\User;

class BudgetPolicy
{
    use HandlesAuthorization;

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
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function view(User $user, Budget $budget)
    {
        return $budget->user_id == $user->id;
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
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function update(User $user, Budget $budget)
    {
        return $budget->user_id == $user->id;
    }

    /**
     * Determine whether the user can duplicate the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function duplicate(User $user, Budget $budget)
    {
        return $budget->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function delete(User $user, Budget $budget)
    {
        return $budget->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function restore(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Budget $budget
     * @return mixed
     */
    public function forceDelete(User $user, Budget $budget)
    {
        //
    }
}
