<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Module;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the module.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Module  $module
     * @return mixed
     */
    public function view(User $user, Module $module)
    {
        //
    }

    /**
     * Determine whether the user can create modules.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the module.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Module  $module
     * @return mixed
     */
    public function update(User $user, Module $module)
    {
        //
    }

    /**
     * Determine whether the user can delete the module.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Module  $module
     * @return mixed
     */
    public function delete(User $user, Module $module)
    {
        //
    }
}
