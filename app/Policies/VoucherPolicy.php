<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class VoucherPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'Spouse';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

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
        $permission = Util::CheckPermission(self::ClASS_NAME,self::CREATE);
        return $permission?true:false;  
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
