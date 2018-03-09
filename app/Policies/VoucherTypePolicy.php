<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\VoucherType;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucherType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\VoucherType  $voucherType
     * @return mixed
     */
    public function view(User $user, VoucherType $voucherType)
    {
        //
    }

    /**
     * Determine whether the user can create voucherTypes.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the voucherType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\VoucherType  $voucherType
     * @return mixed
     */
    public function update(User $user, VoucherType $voucherType)
    {
        //
    }

    /**
     * Determine whether the user can delete the voucherType.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\VoucherType  $voucherType
     * @return mixed
     */
    public function delete(User $user, VoucherType $voucherType)
    {
        //
    }
}
