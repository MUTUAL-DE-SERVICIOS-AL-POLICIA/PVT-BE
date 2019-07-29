<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Models\ComplementaryFactor;
use Muserpol\Helpers\Util;

class ComplementaryFactorPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'ComplementaryFactor';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the ComplementaryFactor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ComplementaryFactor  $ComplementaryFactor
     * @return mixed
     */
    public function view(User $user, ComplementaryFactor $ComplementaryFactor)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::READ);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can create EconomicComplements.
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
     * Determine whether the user can update the ComplementaryFactor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ComplementaryFactor  $ComplementaryFactor
     * @return mixed
     */
    public function update(User $user, ComplementaryFactor $ComplementaryFactor)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can delete the ComplementaryFactor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ComplementaryFactor  $ComplementaryFactor
     * @return mixed
     */
    public function delete(User $user, ComplementaryFactor $ComplementaryFactor)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::DELETE);
        return $permission ? true : false;
    }
}
