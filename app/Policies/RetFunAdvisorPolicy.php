<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunAdvisorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunAdvisor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisor  $retFunAdvisor
     * @return mixed
     */
    public function view(User $user, RetFunAdvisor $retFunAdvisor)
    {
        //
    }

    /**
     * Determine whether the user can create retFunAdvisors.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunAdvisor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisor  $retFunAdvisor
     * @return mixed
     */
    public function update(User $user, RetFunAdvisor $retFunAdvisor)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunAdvisor.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunAdvisor  $retFunAdvisor
     * @return mixed
     */
    public function delete(User $user, RetFunAdvisor $retFunAdvisor)
    {
        //
    }
}
