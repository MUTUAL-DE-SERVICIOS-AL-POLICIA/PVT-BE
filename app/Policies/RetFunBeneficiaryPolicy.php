<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class RetFunBeneficiaryPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'RetFunBeneficiary';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';

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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::CREATE);
        // Log::info(json_encode($permission));
        return $permission?true:false;
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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        // Log::info(json_encode($permission));
        return $permission?true:false;
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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    public function print(User $user, RetFunBeneficiary $retFunBeneficiary)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::PRINT);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

}
