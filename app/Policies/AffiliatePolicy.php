<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;
use Illuminate\Auth\Access\HandlesAuthorization;
use DB;
use Log;
class AffiliatePolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'Affiliate';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    /**
     * Determine whether the user can view the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function view(User $user, Affiliate $affiliate)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create affiliates.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::CREATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can update the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function update(User $user, Affiliate $affiliate)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;     
    }

    /**
     * Determine whether the user can delete the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function delete(User $user, Affiliate $affiliate)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
