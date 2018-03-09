<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\BaseWage;
use Illuminate\Auth\Access\HandlesAuthorization;

class BaseWagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the baseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $baseWage
     * @return mixed
     */
    public function view(User $user, BaseWage $baseWage)
    {
        //
    }

    /**
     * Determine whether the user can create baseWages.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the baseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $baseWage
     * @return mixed
     */
    public function update(User $user, BaseWage $baseWage)
    {
        //
    }

    /**
     * Determine whether the user can delete the baseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $baseWage
     * @return mixed
     */
    public function delete(User $user, BaseWage $baseWage)
    {
        //
    }
}
