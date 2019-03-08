<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;
use Log;
class QuotaAidMortuaryPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'QuotaAidMortuary';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the quotaAidMortuary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\QuotaAidMortuary  $quotaAidMortuary
     * @return mixed
     */
    public function view(User $user, QuotaAidMortuary $quotaAidMortuary)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create quotaAidMortuaries.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::CREATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can update the quotaAidMortuary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\QuotaAidMortuary  $quotaAidMortuary
     * @return mixed
     */
    public function update(User $user, QuotaAidMortuary $quotaAidMortuary)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the quotaAidMortuary.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\QuotaAidMortuary  $quotaAidMortuary
     * @return mixed
     */
    public function delete(User $user, QuotaAidMortuary $quotaAidMortuary)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
    public function qualify(User $user, QuotaAidMortuary $quotaAidMortuary)
    {
        return $quotaAidMortuary->wf_state_current_id == 37 && Util::getRol()->id == 37;
        // && !$quotaAidMortuary->affiliate->selectedContributions() > 0;
    }
}
