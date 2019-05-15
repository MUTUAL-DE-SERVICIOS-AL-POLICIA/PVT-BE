<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class EcoComBeneficiaryPolicy
{
    use HandlesAuthorization;

    const ClASS_NAME = 'EcoComBeneficiary';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';
    /**
     * Determine whether the user can view the ecoComBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComBeneficiary  $ecoComBeneficiary
     * @return mixed
     */
    public function read(User $user, EcoComBeneficiary $ecoComBeneficiary)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::READ);
    }

    /**
     * Determine whether the user can create ecoComBeneficiaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::CREATE);
    }

    /**
     * Determine whether the user can update the ecoComBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComBeneficiary  $ecoComBeneficiary
     * @return mixed
     */
    public function update(User $user, EcoComBeneficiary $ecoComBeneficiary)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
    }

    /**
     * Determine whether the user can delete the ecoComBeneficiary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComBeneficiary  $ecoComBeneficiary
     * @return mixed
     */
    public function delete(User $user, EcoComBeneficiary $ecoComBeneficiary)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::DELETE);
    }
}
