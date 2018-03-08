<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ProcedureRequirement;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcedureRequirementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the procedureRequirement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureRequirement  $procedureRequirement
     * @return mixed
     */
    public function view(User $user, ProcedureRequirement $procedureRequirement)
    {
        //
    }

    /**
     * Determine whether the user can create procedureRequirements.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the procedureRequirement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureRequirement  $procedureRequirement
     * @return mixed
     */
    public function update(User $user, ProcedureRequirement $procedureRequirement)
    {
        //
    }

    /**
     * Determine whether the user can delete the procedureRequirement.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureRequirement  $procedureRequirement
     * @return mixed
     */
    public function delete(User $user, ProcedureRequirement $procedureRequirement)
    {
        //
    }
}
