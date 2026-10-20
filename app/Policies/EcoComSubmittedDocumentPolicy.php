<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\EconomicComplement\EcoComSubmittedDocument;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class EcoComSubmittedDocumentPolicy
{
    use HandlesAuthorization;

    const ClASS_NAME = 'EcoComSubmittedDocument';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the ecoComSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EcoComSubmittedDocument  $ecoComSubmittedDocument
     * @return mixed
     */
    public function view(User $user, EcoComSubmittedDocument $ecoComSubmittedDocument)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::READ);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can create ecoComSubmittedDocuments.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::CREATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can update the ecoComSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EcoComSubmittedDocument  $ecoComSubmittedDocument
     * @return mixed
     */
    public function update(User $user, EcoComSubmittedDocument $ecoComSubmittedDocument)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
        return $permission ? true : false;
    }

    /**
     * Determine whether the user can delete the ecoComSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\EcoComSubmittedDocument  $ecoComSubmittedDocument
     * @return mixed
     */
    public function delete(User $user, EcoComSubmittedDocument $ecoComSubmittedDocument)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME, self::DELETE);
        return $permission ? true : false;
    }
}
