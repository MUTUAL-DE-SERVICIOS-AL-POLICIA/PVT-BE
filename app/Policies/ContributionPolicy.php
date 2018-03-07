<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Contribution\Contribution;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;
class ContributionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //

    }
    public function create(User $user)
    {
        Log::info($user);
        return true;
    }

}
