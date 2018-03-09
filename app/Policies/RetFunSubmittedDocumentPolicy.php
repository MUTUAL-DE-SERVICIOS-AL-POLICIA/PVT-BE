<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetFunSubmittedDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the retFunSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunSubmittedDocument  $retFunSubmittedDocument
     * @return mixed
     */
    public function view(User $user, RetFunSubmittedDocument $retFunSubmittedDocument)
    {
        //
    }

    /**
     * Determine whether the user can create retFunSubmittedDocuments.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the retFunSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunSubmittedDocument  $retFunSubmittedDocument
     * @return mixed
     */
    public function update(User $user, RetFunSubmittedDocument $retFunSubmittedDocument)
    {
        //
    }

    /**
     * Determine whether the user can delete the retFunSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunSubmittedDocument  $retFunSubmittedDocument
     * @return mixed
     */
    public function delete(User $user, RetFunSubmittedDocument $retFunSubmittedDocument)
    {
        //
    }
}
