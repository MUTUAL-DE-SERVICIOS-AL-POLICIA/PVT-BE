<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunApplicant;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunApplicantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunApplicant.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunApplicant  $retFunApplicant
     * @return mixed
     */
    public function view(User $user, RetFunApplicant $retFunApplicant)
    {
        //
    }

    /**
     * Determine whether the user can create retFunApplicants.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunApplicant.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunApplicant  $retFunApplicant
     * @return mixed
     */
    public function update(User $user, RetFunApplicant $retFunApplicant)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunApplicant.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunApplicant  $retFunApplicant
     * @return mixed
     */
    public function delete(User $user, RetFunApplicant $retFunApplicant)
    {
        //
    }
}
