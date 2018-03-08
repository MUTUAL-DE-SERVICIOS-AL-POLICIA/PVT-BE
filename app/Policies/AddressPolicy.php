<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the address.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Address  $address
     * @return mixed
     */
    public function view(User $user, Address $address)
    {
        //
    }

    /**
     * Determine whether the user can create addresses.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the address.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Address  $address
     * @return mixed
     */
    public function update(User $user, Address $address)
    {
        //
    }

    /**
     * Determine whether the user can delete the address.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Address  $address
     * @return mixed
     */
    public function delete(User $user, Address $address)
    {
        //
    }
}
