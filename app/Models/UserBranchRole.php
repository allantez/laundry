<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class UserBranchRole extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'role_id',
        'assigned_by',
        'assigned_at',
        'expires_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns this role assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch for this assignment.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the role for this assignment.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who assigned this role.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Check if assignment is active (not expired).
     */
    public function isActive(): bool
    {
        return !$this->expires_at || $this->expires_at->isFuture();
    }
}
