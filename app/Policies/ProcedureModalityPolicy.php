<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ProcedureModality;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcedureModalityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the procedureModality.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureModality  $procedureModality
     * @return mixed
     */
    public function view(User $user, ProcedureModality $procedureModality)
    {
        //
    }

    /**
     * Determine whether the user can create procedureModalities.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the procedureModality.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureModality  $procedureModality
     * @return mixed
     */
    public function update(User $user, ProcedureModality $procedureModality)
    {
        //
    }

    /**
     * Determine whether the user can delete the procedureModality.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureModality  $procedureModality
     * @return mixed
     */
    public function delete(User $user, ProcedureModality $procedureModality)
    {
        //
    }
}
