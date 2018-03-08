<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\DirectContribution;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectContributionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function view(User $user, DirectContribution $directContribution)
    {
        //
    }

    /**
     * Determine whether the user can create directContributions.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function update(User $user, DirectContribution $directContribution)
    {
        //
    }

    /**
     * Determine whether the user can delete the directContribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return mixed
     */
    public function delete(User $user, DirectContribution $directContribution)
    {
        //
    }
}
