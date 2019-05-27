<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Note;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class NotePolicy
{
    use HandlesAuthorization;

    const ClASS_NAME = 'Note';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';
    /**
     * Determine whether the user can view the note.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\Note  $note
     * @return mixed
     */
    public function read(User $user, Note $note)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::READ);
    }

    /**
     * Determine whether the user can create notes.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::CREATE);
    }

    /**
     * Determine whether the user can update the note.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\Note  $note
     * @return mixed
     */
    public function update(User $user, Note $note)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
    }

    /**
     * Determine whether the user can delete the note.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\Note  $note
     * @return mixed
     */
    public function delete(User $user, Note $note)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::DELETE);
    }
}
