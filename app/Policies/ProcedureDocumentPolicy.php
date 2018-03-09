<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ProcedureDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcedureDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the procedureDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureDocument  $procedureDocument
     * @return mixed
     */
    public function view(User $user, ProcedureDocument $procedureDocument)
    {
        //
    }

    /**
     * Determine whether the user can create procedureDocuments.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the procedureDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureDocument  $procedureDocument
     * @return mixed
     */
    public function update(User $user, ProcedureDocument $procedureDocument)
    {
        //
    }

    /**
     * Determine whether the user can delete the procedureDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ProcedureDocument  $procedureDocument
     * @return mixed
     */
    public function delete(User $user, ProcedureDocument $procedureDocument)
    {
        //
    }
}
