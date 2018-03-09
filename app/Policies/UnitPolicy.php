<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Unit;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the unit.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Unit  $unit
     * @return mixed
     */
    public function view(User $user, Unit $unit)
    {
        //
    }

    /**
     * Determine whether the user can create units.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the unit.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Unit  $unit
     * @return mixed
     */
    public function update(User $user, Unit $unit)
    {
        //
    }

    /**
     * Determine whether the user can delete the unit.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Unit  $unit
     * @return mixed
     */
    public function delete(User $user, Unit $unit)
    {
        //
    }
}
