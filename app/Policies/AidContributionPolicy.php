<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\AidContribution;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class AidContributionPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'AidContribution';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the aidContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidContribution  $aidContribution
     * @return mixed
     */
    public function view(User $user, AidContribution $aidContribution)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create aidContributions.
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
     * Determine whether the user can update the aidContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidContribution  $aidContribution
     * @return mixed
     */
    public function update(User $user, AidContribution $aidContribution)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the aidContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidContribution  $aidContribution
     * @return mixed
     */
    public function delete(User $user, AidContribution $aidContribution)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
