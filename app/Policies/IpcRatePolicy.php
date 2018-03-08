<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Contribution\IpcRate;
use Illuminate\Auth\Access\HandlesAuthorization;

class IpcRatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ipcRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\IpcRate  $ipcRate
     * @return mixed
     */
    public function view(User $user, IpcRate $ipcRate)
    {
        //
    }

    /**
     * Determine whether the user can create ipcRates.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the ipcRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\IpcRate  $ipcRate
     * @return mixed
     */
    public function update(User $user, IpcRate $ipcRate)
    {
        //
    }

    /**
     * Determine whether the user can delete the ipcRate.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\IpcRate  $ipcRate
     * @return mixed
     */
    public function delete(User $user, IpcRate $ipcRate)
    {
        //
    }
}
