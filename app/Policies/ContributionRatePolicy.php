<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\ContributionRate;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContributionRatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the contributionRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return mixed
     */
    public function view(User $user, ContributionRate $contributionRate)
    {
        //
    }

    /**
     * Determine whether the user can create contributionRates.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the contributionRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return mixed
     */
    public function update(User $user, ContributionRate $contributionRate)
    {
        //
    }

    /**
     * Determine whether the user can delete the contributionRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionRate  $contributionRate
     * @return mixed
     */
    public function delete(User $user, ContributionRate $contributionRate)
    {
        //
    }
}
