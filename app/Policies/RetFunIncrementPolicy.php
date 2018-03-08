<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunIncrementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunIncrement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunIncrement  $retFunIncrement
     * @return mixed
     */
    public function view(User $user, RetFunIncrement $retFunIncrement)
    {
        //
    }

    /**
     * Determine whether the user can create retFunIncrements.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunIncrement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunIncrement  $retFunIncrement
     * @return mixed
     */
    public function update(User $user, RetFunIncrement $retFunIncrement)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunIncrement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunIncrement  $retFunIncrement
     * @return mixed
     */
    public function delete(User $user, RetFunIncrement $retFunIncrement)
    {
        //
    }
}
