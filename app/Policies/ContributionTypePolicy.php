<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\ContributionType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContributionTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the contributionType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionType  $contributionType
     * @return mixed
     */
    public function view(User $user, ContributionType $contributionType)
    {
        //
    }

    /**
     * Determine whether the user can create contributionTypes.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the contributionType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionType  $contributionType
     * @return mixed
     */
    public function update(User $user, ContributionType $contributionType)
    {
        //
    }

    /**
     * Determine whether the user can delete the contributionType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ContributionType  $contributionType
     * @return mixed
     */
    public function delete(User $user, ContributionType $contributionType)
    {
        //
    }
}
