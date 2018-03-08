<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunBeneficiaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunBeneficiary  $retFunBeneficiary
     * @return mixed
     */
    public function view(User $user, RetFunBeneficiary $retFunBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can create retFunBeneficiaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunBeneficiary  $retFunBeneficiary
     * @return mixed
     */
    public function update(User $user, RetFunBeneficiary $retFunBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunBeneficiary  $retFunBeneficiary
     * @return mixed
     */
    public function delete(User $user, RetFunBeneficiary $retFunBeneficiary)
    {
        //
    }
}
