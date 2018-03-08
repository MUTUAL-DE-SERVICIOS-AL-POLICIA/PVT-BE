<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\AffiliateState;
use Illuminate\Auth\Access\HandlesAuthorization;

class AffiliateStatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the affiliateState.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateState  $affiliateState
     * @return mixed
     */
    public function view(User $user, AffiliateState $affiliateState)
    {
        //
    }

    /**
     * Determine whether the user can create affiliateStates.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the affiliateState.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateState  $affiliateState
     * @return mixed
     */
    public function update(User $user, AffiliateState $affiliateState)
    {
        //
    }

    /**
     * Determine whether the user can delete the affiliateState.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateState  $affiliateState
     * @return mixed
     */
    public function delete(User $user, AffiliateState $affiliateState)
    {
        //
    }
}
