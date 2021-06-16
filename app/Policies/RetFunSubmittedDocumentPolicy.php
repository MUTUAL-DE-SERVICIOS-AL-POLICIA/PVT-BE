<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class RetFunSubmittedDocumentPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'RetFunSubmittedDocument';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Determine whether the user can view the retFunSubmittedDocument.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\RetFunSubmittedDocument  $retFunSubmittedDocument
     * @return mixed
     */
    public function view(User $user, RetFunSubmittedDocument $retFunSubmittedDocument)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create retFunSubmittedDocuments.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $permission = Util::CheckPermission(self::ClASS_NAME,self::CREATE);
        return $permission?true:false;
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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::UPDATE);
        return $permission?true:false;
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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::DELETE);
        return $permission?true:false;
    }
}
