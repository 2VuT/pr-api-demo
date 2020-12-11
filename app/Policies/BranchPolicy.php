<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Branch  $branch
     * @return mixed
     */
    public function view(User $user, Branch $branch)
    {
        return $user->belongsToBranch($branch);
    }

    /**
     * Determine whether the user can update the branch.
     *
     * @param  \App\User  $user
     * @param  \App\Branch  $branch
     * @return mixed
     */
    public function update(User $user, Branch $branch)
    {
        return $user->belongsToBranch($branch);
    }
}
