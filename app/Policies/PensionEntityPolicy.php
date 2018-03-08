<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\PensionEntity;
use Illuminate\Auth\Access\HandlesAuthorization;

class PensionEntityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the pensionEntity.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\PensionEntity  $pensionEntity
     * @return mixed
     */
    public function view(User $user, PensionEntity $pensionEntity)
    {
        //
    }

    /**
     * Determine whether the user can create pensionEntities.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the pensionEntity.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\PensionEntity  $pensionEntity
     * @return mixed
     */
    public function update(User $user, PensionEntity $pensionEntity)
    {
        //
    }

    /**
     * Determine whether the user can delete the pensionEntity.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\PensionEntity  $pensionEntity
     * @return mixed
     */
    public function delete(User $user, PensionEntity $pensionEntity)
    {
        //
    }
}
