<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\AffiliateFolder;
use Illuminate\Auth\Access\HandlesAuthorization;
use Muserpol\Helpers\Util;

class AffiliateFolderPolicy
{
    use HandlesAuthorization;
    const ClASS_NAME = 'AffiliateFolder';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    /**
     * Determine whether the user can view the affiliateFolder.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateFolder  $affiliateFolder
     * @return mixed
     */
    public function view(User $user, AffiliateFolder $affiliateFolder)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    /**
     * Determine whether the user can create affiliateFolders.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    /**
     * Determine whether the user can update the affiliateFolder.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateFolder  $affiliateFolder
     * @return mixed
     */
    public function update(User $user, AffiliateFolder $affiliateFolder)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    /**
     * Determine whether the user can delete the affiliateFolder.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\AffiliateFolder  $affiliateFolder
     * @return mixed
     */
    public function delete(User $user, AffiliateFolder $affiliateFolder)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::READ);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }

    public function print(User $user, AffiliateFolder $affiliateFolder)
    {
        //
        $permission = Util::CheckPermission(self::ClASS_NAME,self::PRINT);
        // Log::info(json_encode($permission));
        return $permission?true:false;
    }
}
