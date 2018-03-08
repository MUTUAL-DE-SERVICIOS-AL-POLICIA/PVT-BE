<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Kinship;
use Illuminate\Auth\Access\HandlesAuthorization;

class KinshipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the kinship.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Kinship  $kinship
     * @return mixed
     */
    public function view(User $user, Kinship $kinship)
    {
        //
    }

    /**
     * Determine whether the user can create kinships.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the kinship.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Kinship  $kinship
     * @return mixed
     */
    public function update(User $user, Kinship $kinship)
    {
        //
    }

    /**
     * Determine whether the user can delete the kinship.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Kinship  $kinship
     * @return mixed
     */
    public function delete(User $user, Kinship $kinship)
    {
        //
    }
}
