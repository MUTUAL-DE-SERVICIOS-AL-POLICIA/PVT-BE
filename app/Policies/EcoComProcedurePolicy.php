<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class EcoComProcedurePolicy
{
    use HandlesAuthorization;

    const ClASS_NAME = 'EcoComProcedure';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';
    /**
     * Determine whether the user can view the ecoComProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return mixed
     */
    public function read(User $user, EcoComProcedure $ecoComProcedure)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::READ);
    }

    /**
     * Determine whether the user can create ecoComProcedures.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::CREATE);
    }

    /**
     * Determine whether the user can update the ecoComProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return mixed
     */
    public function update(User $user, EcoComProcedure $ecoComProcedure)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
    }

    /**
     * Determine whether the user can delete the ecoComProcedure.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return mixed
     */
    public function delete(User $user, EcoComProcedure $ecoComProcedure)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::DELETE);
    }
}
