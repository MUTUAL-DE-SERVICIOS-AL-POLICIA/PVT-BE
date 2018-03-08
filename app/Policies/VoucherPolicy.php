<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucher.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Voucher  $voucher
     * @return mixed
     */
    public function view(User $user, Voucher $voucher)
    {
        //
    }

    /**
     * Determine whether the user can create vouchers.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the voucher.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Voucher  $voucher
     * @return mixed
     */
    public function update(User $user, Voucher $voucher)
    {
        //
    }

    /**
     * Determine whether the user can delete the voucher.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Voucher  $voucher
     * @return mixed
     */
    public function delete(User $user, Voucher $voucher)
    {
        //
    }
}
