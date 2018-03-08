<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RoleUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoleUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the roleUser.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RoleUser  $roleUser
     * @return mixed
     */
    public function view(User $user, RoleUser $roleUser)
    {
        //
    }

    /**
     * Determine whether the user can create roleUsers.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the roleUser.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RoleUser  $roleUser
     * @return mixed
     */
    public function update(User $user, RoleUser $roleUser)
    {
        //
    }

    /**
     * Determine whether the user can delete the roleUser.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RoleUser  $roleUser
     * @return mixed
     */
    public function delete(User $user, RoleUser $roleUser)
    {
        //
    }
}
