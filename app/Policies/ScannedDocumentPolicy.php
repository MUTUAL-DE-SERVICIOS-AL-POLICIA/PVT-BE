<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ScannedDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScannedDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the scannedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ScannedDocument  $scannedDocument
     * @return mixed
     */
    public function view(User $user, ScannedDocument $scannedDocument)
    {
        //
    }

    /**
     * Determine whether the user can create scannedDocuments.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the scannedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ScannedDocument  $scannedDocument
     * @return mixed
     */
    public function update(User $user, ScannedDocument $scannedDocument)
    {
        //
    }

    /**
     * Determine whether the user can delete the scannedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\ScannedDocument  $scannedDocument
     * @return mixed
     */
    public function delete(User $user, ScannedDocument $scannedDocument)
    {
        //
    }
}
