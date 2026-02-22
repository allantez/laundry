<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\HasBranchRoles;
use App\Models\Branch;
use App\Models\UserBranchRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasBranchRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'branch_id',
        'is_active',
        'phone',
        'profile_photo',
        'job_title',
        'bio',
        'hired_at',
        'last_login_at',
        'last_login_ip',
        'login_count',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'hired_at' => 'datetime',
            'last_login_at' => 'datetime',
            'login_count' => 'integer',
            'preferences' => 'json',
        ];
    }

    /**
     * The "booted" method of the model
     */
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('branch', function ($builder) {
            // Skip if not running in console
            if (app()->runningInConsole()) {
                return;
            }

            /** @var \App\Models\User|null $user */
            $user = auth()->user();

            if (!$user) {
                return;
            }

            // Skip if user doesn't have the branchRoles method
            if (!method_exists($user, 'branchRoles') || !method_exists($user, 'hasBranchRole')) {
                return;
            }

            if ($user && !$user->hasBranchRole('Super Admin', null)) {
                $branchIds = $user->branchRoles()
                    ->whereNotNull('branch_id')
                    ->pluck('branch_id');

                if ($branchIds->isNotEmpty()) {
                    $builder->whereIn('branch_id', $branchIds);
                }
            }
        });
    }

    /**
     * Get the default branch for the user.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Define the relationship to branch role assignments.
     */
    public function branchRoles(): HasMany
    {
        return $this->hasMany(UserBranchRole::class);
    }

    /**
     * Check if user has a role (with branch context)
     *
     * @param string|int|array|\Spatie\Permission\Contracts\Role|\Illuminate\Support\Collection $roles
     * @param string|null $guard
     * @return bool
     */
    public function hasRole($roles, ?string $guard = null): bool
    {
        // First check if user has global role (Super Admin)
        if ($this->hasBranchRole('Super Admin', null)) {
            return true;
        }

        // Get current branch from request/session
        $currentBranch = request()->attributes->get('current_branch')
            ?? session('current_branch');

        if ($currentBranch) {
            // Check branch-specific role
            return $this->hasBranchRole($roles, $currentBranch);
        }

        // Fallback to standard Spatie role check
        return parent::hasRole($roles, $guard);
    }

    /**
     * Check if user has permission (with branch context)
     *
     * @param string|\Spatie\Permission\Contracts\Permission $permission
     * @param string|null $guardName
     * @return bool
     */
    public function hasPermissionTo($permission, ?string $guardName = null): bool
    {
        // Super Admin bypass
        if ($this->hasBranchRole('Super Admin', null)) {
            return true;
        }

        // Get current branch context
        $currentBranch = request()->attributes->get('current_branch')
            ?? session('current_branch');

        if ($currentBranch) {
            // Get permissions for user's roles in this branch
            $permissions = $this->getBranchPermissions($currentBranch);

            $permissionName = $permission instanceof \Spatie\Permission\Contracts\Permission
                ? $permission->name
                : $permission;

            return in_array($permissionName, $permissions);
        }

        // Fallback to standard Spatie permission check
        return parent::hasPermissionTo($permission, $guardName);
    }

    /**
     * Assign role to user in a specific branch
     *
     * @param \Spatie\Permission\Models\Role|string|int $role
     * @param \App\Models\Branch|null $branch
     * @param array|null $options
     * @return \App\Models\UserBranchRole
     */
    public function assignBranchRole(Role|string|int $role, ?Branch $branch = null, ?array $options = []): UserBranchRole
    {
        $roleModel = $role instanceof Role ? $role : null;

        if (!$roleModel && is_string($role)) {
            /** @var \Spatie\Permission\Models\Role|null $roleModel */
            $roleModel = Role::where('name', $role)->first();
        }

        if (!$roleModel && is_numeric($role)) {
            /** @var \Spatie\Permission\Models\Role|null $roleModel */
            $roleModel = Role::find($role);
        }

        if (!$roleModel) {
            throw new \InvalidArgumentException("Role '{$role}' not found.");
        }

        return $this->branchRoles()->create([
            'role_id' => $roleModel->id, // IDE should now recognize this
            'branch_id' => $branch?->id,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'expires_at' => $options['expires_at'] ?? null,
        ]);
    }

    /**
     * Remove role from user in a specific branch
     */
    public function removeBranchRole(Role|string|int $role, ?Branch $branch = null): bool
    {
        $roleId = null;

        if ($role instanceof Role) {
            $roleId = $role->id;
        } elseif (is_numeric($role)) {
            $roleId = (int) $role;
        } else {
            $roleModel = Role::where('name', $role)->first();
            $roleId = $roleModel?->id;
        }

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
     * Get roles for a specific branch
     *
     * @param \App\Models\Branch|null $branch
     * @return \Illuminate\Support\Collection
     */
    public function rolesForBranch(?Branch $branch = null): Collection
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
     * Check if user has role in a specific branch
     */
    public function hasBranchRole(
        string $role,
        Branch|int|null $branch = null
    ): bool {

        $branchId = null;

        if ($branch instanceof Branch) {
            $branchId = $branch->id;
        } elseif (is_int($branch)) {
            $branchId = $branch;
        }

        // Super Admin (global role)
        if (
            $this->branchRoles()
            ->whereNull('branch_id')
            ->whereHas('role', fn($q) => $q->where('name', 'Super Admin'))
            ->exists()
        ) {
            return true;
        }

        return $this->branchRoles()
            ->whereHas('role', fn($q) => $q->where('name', $role))
            ->when(
                $branchId !== null,
                fn($q) => $q->where('branch_id', $branchId),
                fn($q) => $q->whereNull('branch_id')
            )
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }


    /**
     * Get all permissions for the user across all branches
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions(): Collection
    {
        $permissions = collect();

        // Get global roles permissions (Super Admin)
        $globalRoles = $this->branchRoles()
            ->whereNull('branch_id')
            ->with('role.permissions')
            ->get();

        foreach ($globalRoles as $assignment) {
            $permissions = $permissions->concat($assignment->role->permissions);
        }

        // Get branch-specific roles permissions
        $branchRoles = $this->branchRoles()
            ->whereNotNull('branch_id')
            ->with('role.permissions')
            ->get();

        foreach ($branchRoles as $assignment) {
            $permissions = $permissions->concat($assignment->role->permissions);
        }

        return $permissions->unique('id');
    }

    /**
     * Get user's effective permissions for a branch
     *
     * @param \App\Models\Branch|null $branch
     * @return array
     */
    public function getBranchPermissions(?Branch $branch = null): array
    {
        $roles = $this->rolesForBranch($branch);

        return $roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique()->values()->toArray();
    }

    /**
     * Check if user has any active assignments
     *
     * @return bool
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
     * Get current branch from session/request
     *
     * @return \App\Models\Branch|null
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
     * Switch to a different branch
     *
     * @param \App\Models\Branch|null $branch
     * @return bool
     */
    public function switchToBranch(?Branch $branch = null): bool
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
