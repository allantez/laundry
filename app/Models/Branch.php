<?php
// app/Models/Branch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\HasUuid;

class Branch extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'mobile',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'business_hours',
        'is_24_hours',
        'is_active',
        'is_main_branch',
        'opened_at',
        'closed_at',
        'settings',
        'notes',
        'manager_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'business_hours' => 'json',
            'settings' => 'json',
            'is_24_hours' => 'boolean',
            'is_active' => 'boolean',
            'is_main_branch' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'is_24_hours' => false,
        'is_main_branch' => false,
        'country' => 'Kenya',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the manager of the branch.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get all users assigned to this branch.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all customers for this branch.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get all orders for this branch.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all payments for this branch (through orders).
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all inventory items for this branch.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get all expenses for this branch.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get all petty cash transactions for this branch.
     */
    public function pettyCashes(): HasMany
    {
        return $this->hasMany(PettyCash::class);
    }

    /**
     * Get all role assignments for users in this branch.
     */
    public function userBranchRoles(): HasMany
    {
        return $this->hasMany(UserBranchRole::class);
    }

    /**
     * Get all users with roles in this branch (through pivot).
     */
    public function usersWithRoles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branch_roles')
            ->withPivot('role_id', 'assigned_by', 'assigned_at', 'expires_at')
            ->withTimestamps();
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active branches.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive branches.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include the main branch.
     */
    public function scopeMainBranch($query)
    {
        return $query->where('is_main_branch', true);
    }

    /**
     * Scope a query to filter by city.
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope a query to find branch by code.
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    // =========================================================================
    // ACCESSORS & MUTATORS
    // =========================================================================

    /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get formatted phone number.
     */
    public function getFormattedPhoneAttribute(): string
    {
        return $this->phone ?? $this->mobile ?? 'No phone';
    }

    /**
     * Get branch status with color indicator.
     */
    public function getStatusAttribute(): array
    {
        if ($this->deleted_at) {
            return ['label' => 'Closed', 'color' => 'red', 'icon' => 'fa-ban'];
        }

        if (!$this->is_active) {
            return ['label' => 'Inactive', 'color' => 'orange', 'icon' => 'fa-pause-circle'];
        }

        if ($this->closed_at && now()->greaterThan($this->closed_at)) {
            return ['label' => 'Permanently Closed', 'color' => 'red', 'icon' => 'fa-times-circle'];
        }

        return ['label' => 'Active', 'color' => 'green', 'icon' => 'fa-check-circle'];
    }

    /**
     * Get today's business hours.
     */
    public function getTodaysHoursAttribute(): ?string
    {
        if ($this->is_24_hours) {
            return 'Open 24 Hours';
        }

        $day = strtolower(now()->format('l'));
        $hours = $this->business_hours[$day] ?? null;

        return $hours ?? 'Closed';
    }

    /**
     * Check if branch is currently open.
     */
    public function getIsOpenAttribute(): bool
    {
        if (!$this->is_active || $this->deleted_at) {
            return false;
        }

        if ($this->is_24_hours) {
            return true;
        }

        $day = strtolower(now()->format('l'));
        $hours = $this->business_hours[$day] ?? null;

        if (!$hours || $hours === 'Closed') {
            return false;
        }

        // Parse hours and check if current time is within range
        // This is simplified - you might want to implement proper time parsing
        return true;
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Activate the branch.
     */
    public function activate(): bool
    {
        return $this->update([
            'is_active' => true,
            'closed_at' => null,
        ]);
    }

    /**
     * Deactivate the branch.
     */
    public function deactivate(?string $reason = null): bool
    {
        return $this->update([
            'is_active' => false,
            'notes' => $reason ? $this->notes . "\n\nDeactivation reason: " . $reason : $this->notes,
        ]);
    }

    /**
     * Mark branch as permanently closed.
     */
    public function close(): bool
    {
        return $this->update([
            'is_active' => false,
            'closed_at' => now(),
        ]);
    }

    /**
     * Get statistics for this branch.
     */
    public function getStatistics(string $period = 'today'): array
    {
        $query = match ($period) {
            'today' => $this->orders()->whereDate('created_at', today()),
            'week' => $this->orders()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $this->orders()->whereMonth('created_at', now()->month),
            default => $this->orders(),
        };

        return [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'pending_orders' => $this->orders()->where('status', 'pending')->count(),
            'processing_orders' => $this->orders()->where('status', 'processing')->count(),
            'completed_orders' => $this->orders()->where('status', 'completed')->count(),
            'total_customers' => $this->customers()->count(),
            'low_stock_items' => $this->inventoryItems()->whereRaw('current_stock <= reorder_level')->count(),
        ];
    }

    // =========================================================================
    // BOOT METHODS
    // =========================================================================

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        // Ensure only one main branch exists
        static::saving(function ($branch) {
            if ($branch->is_main_branch && $branch->isDirty('is_main_branch')) {
                static::where('is_main_branch', true)
                    ->where('id', '!=', $branch->id)
                    ->update(['is_main_branch' => false]);
            }
        });

        // Generate unique code if not provided
        static::creating(function ($branch) {
            if (empty($branch->code)) {
                $branch->code = static::generateUniqueCode($branch->name);
            }
        });
    }

    /**
     * Generate a unique branch code.
     */
    protected static function generateUniqueCode(string $name): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $number = static::where('code', 'LIKE', $prefix . '%')->count() + 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // =========================================================================
    // FEEDBACK METHODS
    // =========================================================================

    /**
     * Get all feedback for this branch.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    /**
     * Get average rating for this branch.
     */
    public function getAverageRatingAttribute(): ?float
    {
        return $this->feedback()->avg('rating');
    }

    /**
     * Get rating distribution for this branch.
     */
    public function getRatingDistributionAttribute(): array
    {
        $total = $this->feedback()->count();
        if ($total === 0) {
            return [];
        }

        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $this->feedback()->where('rating', $i)->count();
            $distribution[$i] = [
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 2),
            ];
        }

        return $distribution;
    }

    /**
     * Get recent feedback for dashboard.
     */
    public function getRecentFeedback(int $limit = 5): Collection
    {
        return $this->feedback()
            ->with('customer')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
