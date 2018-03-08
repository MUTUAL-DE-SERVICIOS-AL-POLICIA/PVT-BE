<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ProcedureType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcedureTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the procedureType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureType  $procedureType
     * @return mixed
     */
    public function view(User $user, ProcedureType $procedureType)
    {
        //
    }

    /**
     * Determine whether the user can create procedureTypes.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the procedureType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureType  $procedureType
     * @return mixed
     */
    public function update(User $user, ProcedureType $procedureType)
    {
        //
    }

    /**
     * Determine whether the user can delete the procedureType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureType  $procedureType
     * @return mixed
     */
    public function delete(User $user, ProcedureType $procedureType)
    {
        //
    }
}
