<?php

namespace Muserpol\Policies;

use Muserpol\User;
use Muserpol\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the category.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Category  $category
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        //
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param  \Muserpol\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Category  $category
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        //
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  \Muserpol\User  $user
     * @param  \Muserpol\Category  $category
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        //
    }
}
