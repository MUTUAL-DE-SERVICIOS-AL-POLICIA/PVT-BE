<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Breakdown;
use Illuminate\Auth\Access\HandlesAuthorization;

class BreakdownPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the breakdown.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Breakdown  $breakdown
     * @return mixed
     */
    public function view(User $user, Breakdown $breakdown)
    {
        //
    }

    /**
     * Determine whether the user can create breakdowns.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the breakdown.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Breakdown  $breakdown
     * @return mixed
     */
    public function update(User $user, Breakdown $breakdown)
    {
        //
    }

    /**
     * Determine whether the user can delete the breakdown.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Breakdown  $breakdown
     * @return mixed
     */
    public function delete(User $user, Breakdown $breakdown)
    {
        //
    }
}
