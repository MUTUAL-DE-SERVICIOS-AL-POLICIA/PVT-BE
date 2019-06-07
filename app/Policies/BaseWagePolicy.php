<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\BaseWage;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class BaseWagePolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'BaseWage';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the BaseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $BaseWage
     * @return mixed
     */
    public function view(User $user, BaseWage $BaseWage)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::READ);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can create BaseWages.
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
     * Determine whether the user can update the BaseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $BaseWage
     * @return mixed
     */
    public function update(User $user, BaseWage $BaseWage)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can delete the BaseWage.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\BaseWage  $BaseWage
     * @return mixed
     */
    public function delete(User $user, BaseWage $BaseWage)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::DELETE);
        return $permission ? true : false;
    }
}
