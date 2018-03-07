<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Affiliate;
use Illuminate\Auth\Access\HandlesAuthorization;
use Session;
use DB;
class AffiliatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function view(User $user, Affiliate $affiliate)
    {
        //

    }

    /**
     * Determine whether the user can create affiliates.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function update(User $user, Affiliate $affiliate)
    {
        //
        // Log::info($user->id === $affiliate->user_id);
        

        return false;
        // return $user->id === $affiliate->user_id;
    }

    /**
     * Determine whether the user can delete the affiliate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Affiliate  $affiliate
     * @return mixed
     */
    public function delete(User $user, Affiliate $affiliate)
    {
        //
    }
}
