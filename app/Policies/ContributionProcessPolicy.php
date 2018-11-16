<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\ContributionProcess;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class ContributionProcessPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'ContributionProcess';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    /**
     * Determine whether the user can view the contributionProcess.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionProcess  $contributionProcess
     * @return mixed
     */
    public function view(User $user, ContributionProcess $contributionProcess)
    {
        //
    }

    /**
     * Determine whether the user can create contributionProcesses.
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
     * Determine whether the user can update the contributionProcess.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionProcess  $contributionProcess
     * @return mixed
     */
    public function update(User $user, ContributionProcess $contributionProcess)
    {
        //
    }

    /**
     * Determine whether the user can delete the contributionProcess.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionProcess  $contributionProcess
     * @return mixed
     */
    public function delete(User $user, ContributionProcess $contributionProcess)
    {
        //
    }
}
