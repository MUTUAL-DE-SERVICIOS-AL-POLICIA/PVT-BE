<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\Reimbursement;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReimbursementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the reimbursement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Reimbursement  $reimbursement
     * @return mixed
     */
    public function view(User $user, Reimbursement $reimbursement)
    {
        //
    }

    /**
     * Determine whether the user can create reimbursements.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the reimbursement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Reimbursement  $reimbursement
     * @return mixed
     */
    public function update(User $user, Reimbursement $reimbursement)
    {
        //
    }

    /**
     * Determine whether the user can delete the reimbursement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Reimbursement  $reimbursement
     * @return mixed
     */
    public function delete(User $user, Reimbursement $reimbursement)
    {
        //
    }
}
