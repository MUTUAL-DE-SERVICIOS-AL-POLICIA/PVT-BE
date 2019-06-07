<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\ObservationType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class ObservationTypePolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'ObservationType';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const PRINT = 'print';
    /**
     * Determine whether the user can view the ObservationType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\ObservationType  $ObservationType
     * @return mixed
     */
    public function print(User $user, ObservationType $ObservationType)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::PRINT);
    }
    public function read(User $user, ObservationType $ObservationType)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::READ);
    }

    /**
     * Determine whether the user can create ObservationTypes.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::CREATE);
    }

    /**
     * Determine whether the user can update the ObservationType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\ObservationType  $ObservationType
     * @return mixed
     */
    public function update(User $user, ObservationType $ObservationType)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::UPDATE);
    }

    /**
     * Determine whether the user can delete the ObservationType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Models\ObservationType  $ObservationType
     * @return mixed
     */
    public function delete(User $user, ObservationType $ObservationType)
    {
        return Util::CheckPermission(self::ClASS_NAME, self::DELETE);
    }
}
