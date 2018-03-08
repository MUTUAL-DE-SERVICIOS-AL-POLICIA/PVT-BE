<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunLegalGuardianPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardian  $retFunLegalGuardian
     * @return mixed
     */
    public function view(User $user, RetFunLegalGuardian $retFunLegalGuardian)
    {
        //
    }

    /**
     * Determine whether the user can create retFunLegalGuardians.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardian  $retFunLegalGuardian
     * @return mixed
     */
    public function update(User $user, RetFunLegalGuardian $retFunLegalGuardian)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardian  $retFunLegalGuardian
     * @return mixed
     */
    public function delete(User $user, RetFunLegalGuardian $retFunLegalGuardian)
    {
        //
    }
}
