<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\AidCommitment;
use Muserpol\Policies\AidCommitmentPolicy;

use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class AidCommitmentPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'AidCommitment';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the aidCommitmentPolicy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidCommitmentPolicy  $aidCommitmentPolicy
     * @return mixed
     */
    public function view(User $user, AidCommitmentPolicy $aidCommitmentPolicy)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create aidCommitmentPolicies.
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
     * Determine whether the user can update the aidCommitmentPolicy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidCommitmentPolicy  $aidCommitmentPolicy
     * @return mixed
     */
    public function update(User $user, AidCommitmentPolicy $aidCommitmentPolicy)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the aidCommitmentPolicy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AidCommitmentPolicy  $aidCommitmentPolicy
     * @return mixed
     */
    public function delete(User $user, AidCommitmentPolicy $aidCommitmentPolicy)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
