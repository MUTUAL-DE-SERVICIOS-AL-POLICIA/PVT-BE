<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;
use Log;
class ContributionPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'Contribution';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function view(User $user, Contribution $contribution)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create contributions.
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
     * Determine whether the user can update the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function update(User $user, Contribution $contribution)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function delete(User $user, Contribution $contribution)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
