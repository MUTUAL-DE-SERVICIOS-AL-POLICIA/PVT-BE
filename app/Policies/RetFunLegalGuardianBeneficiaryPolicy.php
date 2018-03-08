<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunLegalGuardianBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunLegalGuardianBeneficiaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunLegalGuardianBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardianBeneficiary  $retFunLegalGuardianBeneficiary
     * @return mixed
     */
    public function view(User $user, RetFunLegalGuardianBeneficiary $retFunLegalGuardianBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can create retFunLegalGuardianBeneficiaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunLegalGuardianBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardianBeneficiary  $retFunLegalGuardianBeneficiary
     * @return mixed
     */
    public function update(User $user, RetFunLegalGuardianBeneficiary $retFunLegalGuardianBeneficiary)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunLegalGuardianBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunLegalGuardianBeneficiary  $retFunLegalGuardianBeneficiary
     * @return mixed
     */
    public function delete(User $user, RetFunLegalGuardianBeneficiary $retFunLegalGuardianBeneficiary)
    {
        //
    }
}
