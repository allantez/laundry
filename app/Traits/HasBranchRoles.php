<?php

namespace App\Traits;

use App\Models\Branch;
use App\Models\UserBranchRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

trait HasBranchRoles
{
    /**
     * Get all branch role assignments for the user.
     */
    public function branchRoles(): HasMany
    {
        return $this->hasMany(UserBranchRole::class);
    }

    /**
     * Get roles for a specific branch.
     */
    public function rolesForBranch(?Branch $branch = null)
    {
        $query = $this->branchRoles()->with('role');

        if ($branch) {
            $query->where('branch_id', $branch->id);
        } else {
            $query->whereNull('branch_id');
        }

        return $query->get()->pluck('role');
    }

    /**
     * Assign role to user in a specific branch.
     */
    public function assignBranchRole(Role|string|int $role, ?Branch $branch = null, ?array $options = []): UserBranchRole
    {
        $roleId = match (true) {
            $role instanceof Role => $role->id,
            is_numeric($role) => (int) $role,
            default => Role::where('name', $role)->value('id')
        };

        if (!$roleId) {
            throw new \InvalidArgumentException("Role '{$role}' not found.");
        }

        return $this->branchRoles()->create([
            'role_id' => $roleId,
            'branch_id' => $branch?->id,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'expires_at' => $options['expires_at'] ?? null,
        ]);
    }

    /**
     * Remove role from user in a specific branch.
     */
    public function removeBranchRole(Role|string|int $role, ?Branch $branch = null): bool
    {
        $roleId = match (true) {
            $role instanceof Role => $role->id,
            is_numeric($role) => (int) $role,
            default => Role::where('name', $role)->value('id')
        };

        if (!$roleId) {
            return false;
        }

        return $this->branchRoles()
            ->where('role_id', $roleId)
            ->when($branch, fn($q) => $q->where('branch_id', $branch->id))
            ->when(!$branch, fn($q) => $q->whereNull('branch_id'))
            ->delete() > 0;
    }

    /**
     * Check if user has role in a specific branch.
     */
    public function hasBranchRole(string $role, ?Branch $branch = null): bool
    {
        // Super Admin with global access has all roles
        if ($this->branchRoles()->whereNull('branch_id')->whereHas('role', fn($q) => $q->where('name', 'Super Admin'))->exists()) {
            return true;
        }

        return $this->branchRoles()
            ->whereHas('role', fn($q) => $q->where('name', $role))
            ->when($branch, fn($q) => $q->where('branch_id', $branch->id))
            ->when(!$branch, fn($q) => $q->whereNull('branch_id'))
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Get all branches where user has a specific role.
     */
    public function branchesWithRole(string $role)
    {
        return Branch::whereHas('userRoles', function ($q) use ($role) {
            $q->where('user_id', $this->id)
                ->whereHas('role', fn($r) => $r->where('name', $role));
        });
    }

    /**
     * Get user's effective permissions for a branch.
     */
    public function getBranchPermissions(?Branch $branch = null): array
    {
        $roles = $this->rolesForBranch($branch);

        return $roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique()->values()->toArray();
    }

    /**
     * Check if user has any active assignments.
     */
    public function hasActiveAssignments(): bool
    {
        return $this->branchRoles()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Get current branch from session/request.
     */
    public function getCurrentBranchAttribute(): ?Branch
    {
        $branchId = session('current_branch_id')
            ?? request()->header('X-Branch-ID')
            ?? request()->input('branch_id');

        if ($branchId) {
            return Branch::find($branchId);
        }

        return null;
    }

    /**
     * Switch current branch.
     */
    public function switchToBranch(?Branch $branch): bool
    {
        if (!$branch) {
            session()->forget(['current_branch_id', 'current_branch']);
            return true;
        }

        // Verify user has access to this branch
        if (
            $this->hasBranchRole('Super Admin', null) ||
            $this->branchRoles()->where('branch_id', $branch->id)->exists()
        ) {
            session(['current_branch_id' => $branch->id]);
            session(['current_branch' => $branch]);
            return true;
        }

        return false;
    }
}
