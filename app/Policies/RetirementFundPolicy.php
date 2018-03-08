<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetirementFund;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetirementFundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function view(User $user, RetirementFund $retirementFund)
    {
        //
    }

    /**
     * Determine whether the user can create retirementFunds.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function update(User $user, RetirementFund $retirementFund)
    {
        //
    }

    /**
     * Determine whether the user can delete the retirementFund.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return mixed
     */
    public function delete(User $user, RetirementFund $retirementFund)
    {
        //
    }
}
