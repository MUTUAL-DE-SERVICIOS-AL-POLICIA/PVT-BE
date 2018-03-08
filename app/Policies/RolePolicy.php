<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the role.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        //
    }
}
