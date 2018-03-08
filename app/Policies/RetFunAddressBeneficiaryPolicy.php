<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunAddressBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunAddressBeneficiaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunAddressBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAddressBeneficiary  $retFunAddressBeneficiary
     * @return mixed
     */
    public function view(User $user, RetFunAddressBeneficiary $retFunAddressBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can create retFunAddressBeneficiaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunAddressBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAddressBeneficiary  $retFunAddressBeneficiary
     * @return mixed
     */
    public function update(User $user, RetFunAddressBeneficiary $retFunAddressBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunAddressBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAddressBeneficiary  $retFunAddressBeneficiary
     * @return mixed
     */
    public function delete(User $user, RetFunAddressBeneficiary $retFunAddressBeneficiary)
    {
        //
    }
}
