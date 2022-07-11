<?php

namespace App\Policies;

use App\MeanOfPayment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeanOfPaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\MeanOfPayment  $meanOfPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MeanOfPayment $meanOfPayment)
    {
        return $meanOfPayment->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\MeanOfPayment  $meanOfPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MeanOfPayment $meanOfPayment)
    {
        return $meanOfPayment->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MeanOfPayment  $meanOfPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MeanOfPayment $meanOfPayment)
    {
        return $meanOfPayment->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\MeanOfPayment  $meanOfPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MeanOfPayment $meanOfPayment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MeanOfPayment  $meanOfPayment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MeanOfPayment $meanOfPayment)
    {
        //
    }
}
