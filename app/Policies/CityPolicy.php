<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\City;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the city.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\City  $city
     * @return mixed
     */
    public function view(User $user, City $city)
    {
        //
    }

    /**
     * Determine whether the user can create cities.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the city.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\City  $city
     * @return mixed
     */
    public function update(User $user, City $city)
    {
        //
    }

    /**
     * Determine whether the user can delete the city.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\City  $city
     * @return mixed
     */
    public function delete(User $user, City $city)
    {
        //
    }
}
