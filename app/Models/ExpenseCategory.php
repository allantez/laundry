<?php
// app/Models/ExpenseCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'code',
        'description',
        'parent_id',
        'path',
        'type',
        'is_taxable',
        'tax_rate',
        'tax_code',
        'monthly_budget',
        'yearly_budget',
        'track_budget',
        'alert_on_exceed',
        'budget_alert_threshold',
        'account_code',
        'account_type',
        'requires_approval',
        'approval_threshold',
        'approver_id',
        'color',
        'icon',
        'sort_order',
        'is_active',
        'is_system',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'json',
            'is_taxable' => 'boolean',
            'track_budget' => 'boolean',
            'alert_on_exceed' => 'boolean',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
            'tax_rate' => 'decimal:2',
            'monthly_budget' => 'decimal:2',
            'yearly_budget' => 'decimal:2',
            'budget_alert_threshold' => 'decimal:2',
            'approval_threshold' => 'decimal:2',
            'sort_order' => 'integer',
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
        'type' => 'operational',
        'is_taxable' => false,
        'track_budget' => false,
        'alert_on_exceed' => false,
        'budget_alert_threshold' => 80,
        'requires_approval' => false,
        'is_active' => true,
        'is_system' => false,
        'sort_order' => 0,
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    const TYPE_OPERATIONAL = 'operational';
    const TYPE_ADMINISTRATIVE = 'administrative';
    const TYPE_UTILITY = 'utility';
    const TYPE_SUPPLY = 'supply';
    const TYPE_EQUIPMENT = 'equipment';
    const TYPE_RENT = 'rent';
    const TYPE_SALARY = 'salary';
    const TYPE_MARKETING = 'marketing';
    const TYPE_TRANSPORT = 'transport';
    const TYPE_MAINTENANCE = 'maintenance';
    const TYPE_INSURANCE = 'insurance';
    const TYPE_TAX = 'tax';
    const TYPE_MISCELLANEOUS = 'miscellaneous';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this category belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }

    /**
     * Get all expenses in this category.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }

    /**
     * Get the approver for this category.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include system categories.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope a query to only include non-system categories.
     */
    public function scopeNonSystem($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to only include root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include categories with budgets.
     */
    public function scopeWithBudget($query)
    {
        return $query->where('track_budget', true);
    }

    /**
     * Scope a query to only include taxable categories.
     */
    public function scopeTaxable($query)
    {
        return $query->where('is_taxable', true);
    }

    /**
     * Scope a query to only include categories requiring approval.
     */
    public function scopeRequiresApproval($query)
    {
        return $query->where('requires_approval', true);
    }

    /**
     * Scope a query to search categories.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('account_code', 'like', "%{$search}%");
        });
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the full path name (including parents).
     */
    public function getFullNameAttribute(): string
    {
        if (!$this->parent) {
            return $this->name;
        }

        return $this->parent->full_name . ' > ' . $this->name;
    }

    /**
     * Get type label with formatting.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_OPERATIONAL => 'Operational',
            self::TYPE_ADMINISTRATIVE => 'Administrative',
            self::TYPE_UTILITY => 'Utility',
            self::TYPE_SUPPLY => 'Supplies',
            self::TYPE_EQUIPMENT => 'Equipment',
            self::TYPE_RENT => 'Rent/Lease',
            self::TYPE_SALARY => 'Salaries',
            self::TYPE_MARKETING => 'Marketing',
            self::TYPE_TRANSPORT => 'Transport',
            self::TYPE_MAINTENANCE => 'Maintenance',
            self::TYPE_INSURANCE => 'Insurance',
            self::TYPE_TAX => 'Taxes',
            self::TYPE_MISCELLANEOUS => 'Miscellaneous',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get type color.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_OPERATIONAL => 'blue',
            self::TYPE_ADMINISTRATIVE => 'purple',
            self::TYPE_UTILITY => 'yellow',
            self::TYPE_SUPPLY => 'green',
            self::TYPE_EQUIPMENT => 'orange',
            self::TYPE_RENT => 'red',
            self::TYPE_SALARY => 'indigo',
            self::TYPE_MARKETING => 'pink',
            self::TYPE_TRANSPORT => 'cyan',
            self::TYPE_MAINTENANCE => 'gray',
            self::TYPE_INSURANCE => 'teal',
            self::TYPE_TAX => 'red',
            self::TYPE_MISCELLANEOUS => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_OPERATIONAL => 'fa-cogs',
            self::TYPE_ADMINISTRATIVE => 'fa-building',
            self::TYPE_UTILITY => 'fa-bolt',
            self::TYPE_SUPPLY => 'fa-boxes',
            self::TYPE_EQUIPMENT => 'fa-tools',
            self::TYPE_RENT => 'fa-home',
            self::TYPE_SALARY => 'fa-users',
            self::TYPE_MARKETING => 'fa-chart-line',
            self::TYPE_TRANSPORT => 'fa-truck',
            self::TYPE_MAINTENANCE => 'fa-wrench',
            self::TYPE_INSURANCE => 'fa-shield-alt',
            self::TYPE_TAX => 'fa-file-invoice-dollar',
            self::TYPE_MISCELLANEOUS => 'fa-ellipsis-h',
            default => 'fa-tag',
        };
    }

    /**
     * Get status with color.
     */
    public function getStatusAttribute(): array
    {
        if (!$this->is_active) {
            return [
                'label' => 'Inactive',
                'color' => 'red',
                'icon' => 'fa-ban',
            ];
        }

        return [
            'label' => 'Active',
            'color' => 'green',
            'icon' => 'fa-check-circle',
        ];
    }

    /**
     * Get hierarchy level (depth).
     */
    public function getLevelAttribute(): int
    {
        if (!$this->path) {
            return 0;
        }

        return count(explode('/', trim($this->path, '/')));
    }

    /**
     * Get indented name for display.
     */
    public function getIndentedNameAttribute(): string
    {
        return str_repeat('— ', $this->level) . $this->name;
    }

    /**
     * Get budget utilization for current month.
     */
    public function getMonthlyUtilizationAttribute(): ?array
    {
        if (!$this->track_budget || !$this->monthly_budget) {
            return null;
        }

        $spent = $this->expenses()
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $percentage = $this->monthly_budget > 0
            ? round(($spent / $this->monthly_budget) * 100, 2)
            : 0;

        return [
            'budget' => $this->monthly_budget,
            'spent' => $spent,
            'remaining' => $this->monthly_budget - $spent,
            'percentage' => $percentage,
            'is_exceeded' => $spent > $this->monthly_budget,
            'needs_alert' => $percentage >= $this->budget_alert_threshold,
        ];
    }

    /**
     * Get budget utilization for current year.
     */
    public function getYearlyUtilizationAttribute(): ?array
    {
        if (!$this->track_budget || !$this->yearly_budget) {
            return null;
        }

        $spent = $this->expenses()
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $percentage = $this->yearly_budget > 0
            ? round(($spent / $this->yearly_budget) * 100, 2)
            : 0;

        return [
            'budget' => $this->yearly_budget,
            'spent' => $spent,
            'remaining' => $this->yearly_budget - $spent,
            'percentage' => $percentage,
            'is_exceeded' => $spent > $this->yearly_budget,
        ];
    }

    /**
     * Check if category has children.
     */
    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get total expenses count.
     */
    public function getExpensesCountAttribute(): int
    {
        return $this->expenses()->count();
    }

    /**
     * Get total expenses amount.
     */
    public function getTotalExpensesAttribute(): float
    {
        return $this->expenses()->sum('amount');
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Generate a unique code.
     */
    public static function generateCode(string $name, ?string $type = null): string
    {
        $prefix = match($type) {
            self::TYPE_OPERATIONAL => 'OP',
            self::TYPE_ADMINISTRATIVE => 'AD',
            self::TYPE_UTILITY => 'UT',
            self::TYPE_SUPPLY => 'SP',
            self::TYPE_EQUIPMENT => 'EQ',
            self::TYPE_RENT => 'RN',
            self::TYPE_SALARY => 'SL',
            self::TYPE_MARKETING => 'MK',
            self::TYPE_TRANSPORT => 'TR',
            self::TYPE_MAINTENANCE => 'MT',
            self::TYPE_INSURANCE => 'IN',
            self::TYPE_TAX => 'TX',
            default => 'EX',
        };

        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $code = $prefix . $base;

        $count = 1;
        $originalCode = $code;

        while (self::where('code', $code)->exists()) {
            $code = $originalCode . str_pad($count, 3, '0', STR_PAD_LEFT);
            $count++;
        }

        return $code;
    }

    /**
     * Update the materialized path.
     */
    public function updatePath(): self
    {
        if ($this->parent) {
            $this->path = $this->parent->path . '/' . $this->id;
        } else {
            $this->path = '/' . $this->id;
        }

        $this->saveQuietly();

        // Update children paths
        foreach ($this->children as $child) {
            $child->updatePath();
        }

        return $this;
    }

    /**
     * Get all ancestors.
     */
    public function getAncestors(): array
    {
        if (!$this->path) {
            return [];
        }

        $ids = array_filter(explode('/', $this->path));
        array_pop($ids); // Remove current ID

        return self::whereIn('id', $ids)->orderBy('path')->get()->toArray();
    }

    /**
     * Get all descendants.
     */
    public function getDescendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if this category is a descendant of another.
     */
    public function isDescendantOf(ExpenseCategory $category): bool
    {
        return strpos($this->path, $category->path . '/') === 0;
    }

    /**
     * Check if expense amount requires approval.
     */
    public function requiresApprovalForAmount(float $amount): bool
    {
        return $this->requires_approval
            && $this->approval_threshold
            && $amount > $this->approval_threshold;
    }

    /**
     * Get budget alert if needed.
     */
    public function checkBudgetAlert(): ?array
    {
        if (!$this->track_budget || !$this->alert_on_exceed) {
            return null;
        }

        $monthly = $this->monthly_utilization;

        if ($monthly && $monthly['needs_alert']) {
            return [
                'type' => 'monthly_budget',
                'category' => $this->name,
                'percentage' => $monthly['percentage'],
                'spent' => $monthly['spent'],
                'budget' => $monthly['budget'],
                'message' => "Expense category '{$this->name}' has reached {$monthly['percentage']}% of monthly budget.",
            ];
        }

        return null;
    }

    /**
     * Get expense summary for a period.
     */
    public function getExpenseSummary($startDate = null, $endDate = null): array
    {
        $query = $this->expenses();

        if ($startDate) {
            $query->whereDate('expense_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('expense_date', '<=', $endDate);
        }

        $total = $query->sum('amount');
        $count = $query->count();

        return [
            'total' => $total,
            'formatted_total' => 'KES ' . number_format($total, 2),
            'count' => $count,
            'average' => $count > 0 ? $total / $count : 0,
        ];
    }

    /**
     * Get category tree for dropdown.
     */
    public static function getTreeForBranch($branchId = null): array
    {
        $query = self::with('children');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->root()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return $category->getTreeArray();
            })
            ->toArray();
    }

    /**
     * Get tree array for nested display.
     */
    protected function getTreeArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type_label,
            'type_color' => $this->type_color,
            'icon' => $this->type_icon,
            'has_children' => $this->has_children,
        ];

        if ($this->has_children) {
            $data['children'] = $this->children()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($child) {
                    return $child->getTreeArray();
                })
                ->toArray();
        }

        return $data;
    }

    /**
     * Get summary report.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'code' => $this->code,
            'type' => $this->type_label,
            'type_icon' => $this->type_icon,
            'type_color' => $this->type_color,
            'status' => $this->status,
            'level' => $this->level,
            'has_children' => $this->has_children,
            'expenses_count' => $this->expenses_count,
            'total_expenses' => 'KES ' . number_format($this->total_expenses, 2),
            'monthly_utilization' => $this->monthly_utilization,
            'yearly_utilization' => $this->yearly_utilization,
            'requires_approval' => $this->requires_approval,
            'approval_threshold' => $this->approval_threshold,
            'approver' => $this->approver?->name,
            'is_taxable' => $this->is_taxable,
            'tax_rate' => $this->tax_rate,
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

        static::creating(function ($category) {
            // Generate slug if not provided
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

            // Generate code if not provided
            if (empty($category->code)) {
                $category->code = self::generateCode($category->name, $category->type);
            }
        });

        static::created(function ($category) {
            // Update path
            $category->updatePath();
        });

        static::updating(function ($category) {
            // Update slug if name changed
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }

            // Check if parent changed
            if ($category->isDirty('parent_id')) {
                $category->updatePath();
            }
        });

        static::deleting(function ($category) {
            // Prevent deletion of system categories
            if ($category->is_system) {
                throw new \Exception('System categories cannot be deleted.');
            }

            // Check if there are any expenses
            if ($category->expenses()->exists()) {
                throw new \Exception('Cannot delete category with existing expenses.');
            }

            // Check if there are child categories
            if ($category->children()->exists()) {
                throw new \Exception('Cannot delete category with child categories.');
            }
        });

        // Add branch scope for non-admin users
        static::addGlobalScope('branch', function ($builder) {
            if (app()->runningInConsole()) {
                return;
            }

            $user = auth()->user();

            if (!$user || !is_object($user)) {
                return;
            }

            if (!method_exists($user, 'hasBranchRole') || !method_exists($user, 'branchRoles')) {
                return;
            }

            if (!$user->hasBranchRole('Super Admin', null)) {
                $branchIds = $user->branchRoles()
                    ->whereNotNull('branch_id')
                    ->pluck('branch_id');

                if ($branchIds->isNotEmpty()) {
                    $builder->whereIn('branch_id', $branchIds);
                }
            }
        });
    }
}
