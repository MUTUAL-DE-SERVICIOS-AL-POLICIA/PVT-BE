<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\ContributionCommitment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class ContributionCommitmentPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'ContributionCommitment';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the contributionCommitment.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionCommitment  $contributionCommitment
     * @return mixed
     */
    public function view(User $user, ContributionCommitment $contributionCommitment)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create contributionCommitments.
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
     * Determine whether the user can update the contributionCommitment.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionCommitment  $contributionCommitment
     * @return mixed
     */
    public function update(User $user, ContributionCommitment $contributionCommitment)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the contributionCommitment.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionCommitment  $contributionCommitment
     * @return mixed
     */
    public function delete(User $user, ContributionCommitment $contributionCommitment)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
