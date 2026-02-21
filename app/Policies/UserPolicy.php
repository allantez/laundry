<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // User can view themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Super Admin can view all
        if ($user->hasBranchRole('Super Admin', null)) {
            return true;
        }

        // Branch Manager can view users in their branch
        if ($user->hasBranchRole('Branch Manager')) {
            $currentBranch = $user->current_branch;
            if ($currentBranch && $model->branch_id === $currentBranch->id) {
                return true;
            }
        }

        return $user->hasPermissionTo('view users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // User can update themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Prevent editing Super Admin by non-super admin
        if ($model->hasBranchRole('Super Admin', null) && !$user->hasBranchRole('Super Admin', null)) {
            return false;
        }

        // Super Admin can edit all
        if ($user->hasBranchRole('Super Admin', null)) {
            return true;
        }

        // Branch Manager can edit users in their branch
        if ($user->hasBranchRole('Branch Manager')) {
            $currentBranch = $user->current_branch;
            if ($currentBranch && $model->branch_id === $currentBranch->id) {
                return true;
            }
        }

        return $user->hasPermissionTo('edit users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Prevent deleting yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Prevent deleting Super Admin
        if ($model->hasBranchRole('Super Admin', null)) {
            return false;
        }

        // Super Admin can delete all
        if ($user->hasBranchRole('Super Admin', null)) {
            return true;
        }

        // Branch Manager can delete users in their branch
        if ($user->hasBranchRole('Branch Manager')) {
            $currentBranch = $user->current_branch;
            if ($currentBranch && $model->branch_id === $currentBranch->id) {
                return $user->hasPermissionTo('delete users');
            }
        }

        return false;
    }

    /**
     * Determine whether the user can assign roles.
     */
    public function assignRoles(User $user, User $model): bool
    {
        // Prevent assigning roles to Super Admin by non-super admin
        if ($model->hasBranchRole('Super Admin', null) && !$user->hasBranchRole('Super Admin', null)) {
            return false;
        }

        return $user->hasPermissionTo('assign roles');
    }
}
