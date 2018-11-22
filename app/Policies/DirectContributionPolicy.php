<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\DirectContribution;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class DirectContributionPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'ContributionProcess';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function view(User $user, DirectContribution $directContribution)
    {
        //
    }

    /**
     * Determine whether the user can create directContributions.
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
     * Determine whether the user can update the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function update(User $user, DirectContribution $directContribution)
    {
        //
    }

    /**
     * Determine whether the user can delete the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function delete(User $user, DirectContribution $directContribution)
    {
        //
    }
}
