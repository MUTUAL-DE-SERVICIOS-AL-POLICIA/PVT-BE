<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Spouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpousePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the spouse.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Spouse  $spouse
     * @return mixed
     */
    public function view(User $user, Spouse $spouse)
    {
        //
    }

    /**
     * Determine whether the user can create spouses.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the spouse.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Spouse  $spouse
     * @return mixed
     */
    public function update(User $user, Spouse $spouse)
    {
        //
    }

    /**
     * Determine whether the user can delete the spouse.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Spouse  $spouse
     * @return mixed
     */
    public function delete(User $user, Spouse $spouse)
    {
        //
    }
}
