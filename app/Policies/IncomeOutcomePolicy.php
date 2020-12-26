<?php

namespace App\Policies;

use App\Income;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomeOutcomePolicy
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
     * @param  \App\Income  $income
     * @return mixed
     */
    public function viewIncomeOutcome(User $user, $incomeOutcome)
    {
        return $user->id == $incomeOutcome->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Income  $income_id
     * @return mixed
     */
    public function update(User $user, $incomeOutcome)
    {
        return $user->id == $incomeOutcome->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Income  $income
     * @return mixed
     */
    public function delete(User $user, Income $income)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Income  $income
     * @return mixed
     */
    public function restore(User $user, Income $income)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Income  $income
     * @return mixed
     */
    public function forceDelete(User $user, Income $income)
    {
        //
    }
}
