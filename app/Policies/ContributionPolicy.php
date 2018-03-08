<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;
class ContributionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function view(User $user, Contribution $contribution)
    {
        //
    }

    /**
     * Determine whether the user can create contributions.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        Log::info($user);
        return true;

    }

    /**
     * Determine whether the user can update the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function update(User $user, Contribution $contribution)
    {
        //
    }

    /**
     * Determine whether the user can delete the contribution.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Contribution  $contribution
     * @return mixed
     */
    public function delete(User $user, Contribution $contribution)
    {
        //
    }
}
