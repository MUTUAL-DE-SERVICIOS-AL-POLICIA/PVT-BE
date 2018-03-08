<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Hierarchy;
use Illuminate\Auth\Access\HandlesAuthorization;

class HierarchyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the hierarchy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Hierarchy  $hierarchy
     * @return mixed
     */
    public function view(User $user, Hierarchy $hierarchy)
    {
        //
    }

    /**
     * Determine whether the user can create hierarchies.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the hierarchy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Hierarchy  $hierarchy
     * @return mixed
     */
    public function update(User $user, Hierarchy $hierarchy)
    {
        //
    }

    /**
     * Determine whether the user can delete the hierarchy.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Hierarchy  $hierarchy
     * @return mixed
     */
    public function delete(User $user, Hierarchy $hierarchy)
    {
        //
    }
}
