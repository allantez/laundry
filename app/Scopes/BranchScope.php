<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        // Skip for Super Admin with global access
        if ($user->hasBranchRole('Super Admin', null)) {
            return;
        }

        // Get branches this user has access to
        $branchIds = $user->branchRoles()
            ->whereNotNull('branch_id')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->pluck('branch_id')
            ->unique();

        if ($branchIds->isNotEmpty()) {
            $builder->whereIn('branch_id', $branchIds);
        } else {
            // Force no results if user has no branch access
            $builder->whereRaw('1 = 0');
        }
    }
}
