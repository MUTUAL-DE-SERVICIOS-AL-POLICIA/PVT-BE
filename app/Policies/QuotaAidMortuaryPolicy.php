<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;
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
    }
}
