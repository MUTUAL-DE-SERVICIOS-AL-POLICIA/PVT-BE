<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunProcedurePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunProcedure  $retFunProcedure
     * @return mixed
     */
    public function view(User $user, RetFunProcedure $retFunProcedure)
    {
        //
    }

    /**
     * Determine whether the user can create retFunProcedures.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunProcedure  $retFunProcedure
     * @return mixed
     */
    public function update(User $user, RetFunProcedure $retFunProcedure)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunProcedure  $retFunProcedure
     * @return mixed
     */
    public function delete(User $user, RetFunProcedure $retFunProcedure)
    {
        //
    }
}
