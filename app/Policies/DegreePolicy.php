<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Degree;
use Illuminate\Auth\Access\HandlesAuthorization;

class DegreePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the degree.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Degree  $degree
     * @return mixed
     */
    public function view(User $user, Degree $degree)
    {
        //
    }

    /**
     * Determine whether the user can create degrees.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the degree.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Degree  $degree
     * @return mixed
     */
    public function update(User $user, Degree $degree)
    {
        //
    }

    /**
     * Determine whether the user can delete the degree.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Degree  $degree
     * @return mixed
     */
    public function delete(User $user, Degree $degree)
    {
        //
    }
}
