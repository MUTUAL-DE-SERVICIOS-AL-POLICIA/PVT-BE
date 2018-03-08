<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\AffiliateFolder;
use Illuminate\Auth\Access\HandlesAuthorization;

class AffiliateFolderPolicy
{
    use HandlesAuthorization;

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
    }
}
