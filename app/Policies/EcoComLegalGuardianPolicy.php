<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class EcoComLegalGuardianPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'EcoComLegalGuardian';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';

    /**
     * Determine whether the user can view the ecoComLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComLegalGuardian  $ecoComLegalGuardian
     * @return mixed
     */
    public function read(User $user, EcoComLegalGuardian $ecoComLegalGuardian)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::READ);

    }

    /**
     * Determine whether the user can create ecoComLegalGuardians.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::CREATE);
    }

    /**
     * Determine whether the user can update the ecoComLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComLegalGuardian  $ecoComLegalGuardian
     * @return mixed
     */
    public function update(User $user, EcoComLegalGuardian $ecoComLegalGuardian)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
    }

    /**
     * Determine whether the user can delete the ecoComLegalGuardian.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComLegalGuardian  $ecoComLegalGuardian
     * @return mixed
     */
    public function delete(User $user, EcoComLegalGuardian $ecoComLegalGuardian)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::DELETE);
    }
}
