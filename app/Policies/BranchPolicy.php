<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view branches');
    }

    public function view(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Branch Manager')) {
            return $user->branch_id === $branch->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create branches');
    }

    public function update(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Branch Manager')) {
            return $user->branch_id === $branch->id;
        }

        return false;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('Super Admin');
    }
}
