<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunAdvisorBeneficiaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunAdvisorBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisorBeneficiary  $retFunAdvisorBeneficiary
     * @return mixed
     */
    public function view(User $user, RetFunAdvisorBeneficiary $retFunAdvisorBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can create retFunAdvisorBeneficiaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunAdvisorBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisorBeneficiary  $retFunAdvisorBeneficiary
     * @return mixed
     */
    public function update(User $user, RetFunAdvisorBeneficiary $retFunAdvisorBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunAdvisorBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisorBeneficiary  $retFunAdvisorBeneficiary
     * @return mixed
     */
    public function delete(User $user, RetFunAdvisorBeneficiary $retFunAdvisorBeneficiary)
    {
        //
    }
}
