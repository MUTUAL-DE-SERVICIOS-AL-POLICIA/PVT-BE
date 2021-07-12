<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class EconomicComplementPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'EconomicComplement';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the EconomicComplement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EconomicComplement  $EconomicComplement
     * @return mixed
     */
    public function read(User $user, EconomicComplement $EconomicComplement)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::READ);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can create EconomicComplements.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::CREATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can update the EconomicComplement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EconomicComplement  $EconomicComplement
     * @return mixed
     */
    public function update(User $user, EconomicComplement $EconomicComplement)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can delete the EconomicComplement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EconomicComplement  $EconomicComplement
     * @return mixed
     */
    public function delete(User $user, EconomicComplement $EconomicComplement)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::DELETE);
        return $permission ? true : false;
    }
    public function qualify(User $user, EconomicComplement $EconomicComplement)
    {
        return $EconomicComplement->wf_current_state_id == 3 && Util::getRol()->id == 4 && $EconomicComplement->inbox_state == false;
    }
    public function amortize(User $user,EconomicComplement $EconomicComplement)
    {
        $observation = $EconomicComplement->observations()->where('id', Util::getObservationIdFromRoleId())->first();
        return $EconomicComplement->wf_current_state_id == 3 && array_search(Util::getRol()->id, [4,7,16]) !== false;
   	;
    }
}
