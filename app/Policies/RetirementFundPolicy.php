<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetirementFund;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;
use Log;
class RetirementFundPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'RetirementFund';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function view(User $user, RetirementFund $retirementFund)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create retirementFunds.
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
     * Determine whether the user can update the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function update(User $user, RetirementFund $retirementFund)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function delete(User $user, RetirementFund $retirementFund)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }
}
