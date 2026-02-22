<?php
// app/Models/InventoryItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'supplier_id',
        'name',
        'sku',
        'barcode',
        'category',
        'sub_category',
        'description',
        'brand',
        'model',
        'unit_type',
        'unit_size',
        'unit_size_type',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'reorder_point',
        'reorder_quantity',
        'unit_cost',
        'average_cost',
        'last_cost',
        'selling_price',
        'markup_percentage',
        'location',
        'aisle',
        'rack',
        'bin',
        'track_expiry',
        'expiry_date',
        'shelf_life_days',
        'last_expiry_check',
        'track_batches',
        'batch_number',
        'lot_number',
        'manufacturing_date',
        'total_value',
        'is_active',
        'is_taxable',
        'tax_rate',
        'alert_on_low_stock',
        'alert_on_expiry',
        'alert_before_days',
        'image',
        'images',
        'documents',
        'specifications',
        'ingredients',
        'safety_info',
        'total_quantity_used',
        'total_quantity_purchased',
        'total_quantity_wasted',
        'last_used_at',
        'last_purchased_at',
        'last_counted_at',
        'notes',
        'metadata',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'json',
            'documents' => 'json',
            'specifications' => 'json',
            'ingredients' => 'json',
            'safety_info' => 'json',
            'metadata' => 'json',
            'tags' => 'json',
            'unit_size' => 'decimal:2',
            'current_stock' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'maximum_stock' => 'decimal:2',
            'reorder_point' => 'decimal:2',
            'reorder_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'average_cost' => 'decimal:2',
            'last_cost' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'markup_percentage' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'total_value' => 'decimal:2',
            'total_quantity_used' => 'decimal:2',
            'total_quantity_purchased' => 'decimal:2',
            'total_quantity_wasted' => 'decimal:2',
            'alert_before_days' => 'integer',
            'shelf_life_days' => 'integer',
            'track_expiry' => 'boolean',
            'track_batches' => 'boolean',
            'is_active' => 'boolean',
            'is_taxable' => 'boolean',
            'alert_on_low_stock' => 'boolean',
            'alert_on_expiry' => 'boolean',
            'expiry_date' => 'date',
            'manufacturing_date' => 'date',
            'last_expiry_check' => 'datetime',
            'last_used_at' => 'datetime',
            'last_purchased_at' => 'datetime',
            'last_counted_at' => 'datetime',
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
        'current_stock' => 0,
        'minimum_stock' => 0,
        'reorder_point' => 0,
        'unit_cost' => 0,
        'average_cost' => 0,
        'total_value' => 0,
        'total_quantity_used' => 0,
        'total_quantity_purchased' => 0,
        'total_quantity_wasted' => 0,
        'is_active' => true,
        'is_taxable' => true,
        'alert_on_low_stock' => true,
        'alert_on_expiry' => true,
        'alert_before_days' => 30,
        'track_expiry' => false,
        'track_batches' => false,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this inventory item belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the supplier of this inventory item.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all stock movements for this item.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(InventoryStockMovement::class);
    }

    /**
     * Get all stock alerts for this item.
     */
    public function stockAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    /**
     * Get all service items that use this inventory.
     */
    public function serviceItems(): HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }

    /**
     * Get all order items that used this inventory.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive items.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by supplier.
     */
    public function scopeFromSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope a query to only include items low on stock.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock')
            ->orWhereRaw('current_stock <= reorder_point');
    }

    /**
     * Scope a query to only include items out of stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    /**
     * Scope a query to only include items nearing expiry.
     */
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('track_expiry', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    /**
     * Scope a query to only include expired items.
     */
    public function scopeExpired($query)
    {
        return $query->where('track_expiry', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    /**
     * Scope a query to search items.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('batch_number', 'like', "%{$search}%")
                ->orWhere('lot_number', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by stock level.
     */
    public function scopeOrderByStockLevel($query, string $direction = 'asc')
    {
        return $query->orderBy('current_stock', $direction);
    }

    /**
     * Scope a query to get items needing reorder.
     */
    public function scopeNeedsReorder($query)
    {
        return $query->where('current_stock', '<=', 'reorder_point')
            ->where('reorder_point', '>', 0);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the display name with SKU.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->sku})";
    }

    /**
     * Get the full location string.
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->location,
            $this->aisle,
            $this->rack,
            $this->bin,
        ]);

        return implode(' > ', $parts) ?: 'Not assigned';
    }

    /**
     * Get stock status with color.
     */
    public function getStockStatusAttribute(): array
    {
        if ($this->current_stock <= 0) {
            return [
                'label' => 'Out of Stock',
                'color' => 'red',
                'icon' => 'fa-times-circle',
                'badge' => 'bg-red-100 text-red-800',
            ];
        }

        if ($this->current_stock <= $this->minimum_stock) {
            return [
                'label' => 'Low Stock',
                'color' => 'orange',
                'icon' => 'fa-exclamation-triangle',
                'badge' => 'bg-orange-100 text-orange-800',
            ];
        }

        if ($this->maximum_stock && $this->current_stock >= $this->maximum_stock) {
            return [
                'label' => 'Overstock',
                'color' => 'yellow',
                'icon' => 'fa-arrow-up',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ];
        }

        return [
            'label' => 'In Stock',
            'color' => 'green',
            'icon' => 'fa-check-circle',
            'badge' => 'bg-green-100 text-green-800',
        ];
    }

    /**
     * Get expiry status with color.
     */
    public function getExpiryStatusAttribute(): ?array
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return null;
        }

        $daysUntilExpiry = now()->diffInDays($this->expiry_date, false);

        if ($daysUntilExpiry < 0) {
            return [
                'label' => 'Expired',
                'color' => 'red',
                'icon' => 'fa-skull',
                'days' => abs($daysUntilExpiry) . ' days ago',
            ];
        }

        if ($daysUntilExpiry <= $this->alert_before_days) {
            return [
                'label' => 'Expiring Soon',
                'color' => 'orange',
                'icon' => 'fa-clock',
                'days' => $daysUntilExpiry . ' days left',
            ];
        }

        return [
            'label' => 'Valid',
            'color' => 'green',
            'icon' => 'fa-check',
            'days' => $daysUntilExpiry . ' days left',
        ];
    }

    /**
     * Get formatted unit cost.
     */
    public function getFormattedUnitCostAttribute(): string
    {
        return 'KES ' . number_format($this->unit_cost, 2);
    }

    /**
     * Get formatted total value.
     */
    public function getFormattedTotalValueAttribute(): string
    {
        return 'KES ' . number_format($this->total_value, 2);
    }

    /**
     * Get the unit display (e.g., "5 L" or "500 ml").
     */
    public function getUnitDisplayAttribute(): string
    {
        if ($this->unit_size && $this->unit_size_type) {
            return $this->unit_size . ' ' . $this->unit_size_type;
        }

        return $this->unit_type;
    }

    /**
     * Check if item needs reorder.
     */
    public function getNeedsReorderAttribute(): bool
    {
        return $this->reorder_point > 0 && $this->current_stock <= $this->reorder_point;
    }

    /**
     * Get suggested reorder quantity.
     */
    public function getSuggestedReorderQuantityAttribute(): float
    {
        if ($this->reorder_quantity) {
            return $this->reorder_quantity;
        }

        if ($this->maximum_stock) {
            return max(0, $this->maximum_stock - $this->current_stock);
        }

        return $this->minimum_stock * 2;
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Generate a unique SKU.
     */
    public static function generateSku(string $category, string $name): string
    {
        $categoryPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
        $namePrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $random = strtoupper(Str::random(4));

        $sku = $categoryPrefix . $namePrefix . $random;

        // Ensure uniqueness
        while (self::where('sku', $sku)->exists()) {
            $random = strtoupper(Str::random(4));
            $sku = $categoryPrefix . $namePrefix . $random;
        }

        return $sku;
    }

    /**
     * Add stock to inventory.
     */
    public function addStock(float $quantity, float $cost, string $reason, ?User $user = null, ?array $metadata = []): self
    {
        $previousStock = $this->current_stock;
        $previousAverage = $this->average_cost;

        // Update current stock
        $this->current_stock += $quantity;

        // Update average cost (weighted average)
        $totalValue = ($previousAverage * $previousStock) + ($cost * $quantity);
        $this->average_cost = $totalValue / $this->current_stock;

        // Update other cost fields
        $this->last_cost = $cost;
        $this->unit_cost = $cost; // Optionally update unit cost

        // Update totals
        $this->total_quantity_purchased += $quantity;
        $this->total_value = $this->current_stock * $this->average_cost;
        $this->last_purchased_at = now();

        $this->save();

        // Create stock movement record
        $this->stockMovements()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'unit_cost' => $cost,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'reason' => $reason,
            'reference_type' => 'purchase',
            'reference_id' => $metadata['purchase_id'] ?? null,
            'created_by' => $user?->id,
            'metadata' => $metadata,
        ]);

        return $this;
    }

    /**
     * Remove stock from inventory.
     */
    public function removeStock(float $quantity, string $reason, ?User $user = null, ?array $metadata = []): self
    {
        if ($quantity > $this->current_stock) {
            throw new \Exception("Insufficient stock. Available: {$this->current_stock}, Requested: {$quantity}");
        }

        $previousStock = $this->current_stock;

        // Update current stock
        $this->current_stock -= $quantity;

        // Update totals
        $this->total_quantity_used += $quantity;
        $this->total_value = $this->current_stock * $this->average_cost;
        $this->last_used_at = now();

        $this->save();

        // Create stock movement record
        $this->stockMovements()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'unit_cost' => $this->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'reason' => $reason,
            'reference_type' => $metadata['reference_type'] ?? null,
            'reference_id' => $metadata['reference_id'] ?? null,
            'created_by' => $user?->id,
            'metadata' => $metadata,
        ]);

        // Check for low stock and create alert if needed
        if ($this->current_stock <= $this->minimum_stock && $this->alert_on_low_stock) {
            $this->createLowStockAlert();
        }

        return $this;
    }

    /**
     * Adjust stock (for corrections).
     */
    public function adjustStock(float $newQuantity, string $reason, ?User $user = null, ?array $metadata = []): self
    {
        $previousStock = $this->current_stock;
        $difference = $newQuantity - $previousStock;

        $this->current_stock = $newQuantity;
        $this->total_value = $newQuantity * $this->average_cost;
        $this->last_counted_at = now();

        $this->save();

        // Create stock movement record
        $this->stockMovements()->create([
            'type' => $difference > 0 ? 'in' : 'out',
            'quantity' => abs($difference),
            'unit_cost' => $this->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newQuantity,
            'reason' => 'Adjustment: ' . $reason,
            'reference_type' => 'adjustment',
            'created_by' => $user?->id,
            'metadata' => $metadata,
        ]);

        return $this;
    }

    /**
     * Create low stock alert.
     */
    public function createLowStockAlert(): self
    {
        // Check if there's already an unresolved alert
        $existingAlert = $this->stockAlerts()
            ->where('type', 'low_stock')
            ->where('is_resolved', false)
            ->first();

        if (!$existingAlert) {
            $this->stockAlerts()->create([
                'type' => 'low_stock',
                'message' => "Low stock alert: {$this->name} is below minimum level. Current: {$this->current_stock}, Minimum: {$this->minimum_stock}",
                'threshold_value' => $this->minimum_stock,
                'current_value' => $this->current_stock,
                'alerted_at' => now(),
            ]);
        }

        return $this;
    }

    /**
     * Create expiry alert.
     */
    public function createExpiryAlert(): self
    {
        if (!$this->expiry_date) {
            return $this;
        }

        $daysUntilExpiry = now()->diffInDays($this->expiry_date, false);

        if ($daysUntilExpiry <= $this->alert_before_days && $daysUntilExpiry > 0) {
            // Check if there's already an unresolved alert
            $existingAlert = $this->stockAlerts()
                ->where('type', 'expiry')
                ->where('is_resolved', false)
                ->first();

            if (!$existingAlert) {
                $this->stockAlerts()->create([
                    'type' => 'expiry',
                    'message' => "Expiry alert: {$this->name} will expire in {$daysUntilExpiry} days on {$this->expiry_date->format('Y-m-d')}",
                    'threshold_value' => $this->alert_before_days,
                    'current_value' => $daysUntilExpiry,
                    'alerted_at' => now(),
                ]);
            }
        }

        return $this;
    }

    /**
     * Check and create alerts.
     */
    public function checkAlerts(): self
    {
        if ($this->alert_on_low_stock && $this->current_stock <= $this->minimum_stock) {
            $this->createLowStockAlert();
        }

        if ($this->alert_on_expiry && $this->expiry_date) {
            $this->createExpiryAlert();
        }

        return $this;
    }

    /**
     * Mark as wasted/expired.
     */
    public function markAsWasted(float $quantity, string $reason, ?User $user = null): self
    {
        if ($quantity > $this->current_stock) {
            throw new \Exception("Cannot waste more than available stock.");
        }

        $previousStock = $this->current_stock;

        $this->current_stock -= $quantity;
        $this->total_quantity_wasted += $quantity;
        $this->total_value = $this->current_stock * $this->average_cost;

        $this->save();

        // Create stock movement record
        $this->stockMovements()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'unit_cost' => $this->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'reason' => 'Waste: ' . $reason,
            'reference_type' => 'waste',
            'created_by' => $user?->id,
        ]);

        return $this;
    }

    /**
     * Update total value.
     */
    public function updateTotalValue(): self
    {
        $this->total_value = $this->current_stock * $this->average_cost;
        $this->saveQuietly();

        return $this;
    }

    /**
     * Get inventory valuation.
     */
    public function getValuation(): array
    {
        return [
            'current_stock' => $this->current_stock,
            'unit_cost' => $this->average_cost,
            'total_value' => $this->total_value,
            'formatted_total' => $this->formatted_total_value,
            'by_cost_method' => [
                'fifo' => $this->calculateFifoValue(),
                'average' => $this->total_value,
            ],
        ];
    }

    /**
     * Calculate FIFO value (simplified).
     */
    protected function calculateFifoValue(): float
    {
        // This would need purchase history to calculate accurately
        // For now, return average cost
        return $this->total_value;
    }

    /**
     * Get stock movement history.
     */
    public function getMovementHistory(int $limit = 100): array
    {
        return $this->stockMovements()
            ->with('createdBy')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'type' => $movement->type,
                    'quantity' => $movement->quantity,
                    'previous_stock' => $movement->previous_stock,
                    'new_stock' => $movement->new_stock,
                    'reason' => $movement->reason,
                    'created_at' => $movement->created_at->format('Y-m-d H:i'),
                    'created_by' => $movement->createdBy?->name ?? 'System',
                ];
            })
            ->toArray();
    }

    /**
     * Get summary report.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'category' => $this->category,
            'current_stock' => $this->current_stock,
            'unit_display' => $this->unit_display,
            'stock_status' => $this->stock_status,
            'expiry_status' => $this->expiry_status,
            'location' => $this->full_location,
            'supplier' => $this->supplier?->name,
            'unit_cost' => $this->formatted_unit_cost,
            'total_value' => $this->formatted_total_value,
            'needs_reorder' => $this->needs_reorder,
            'reorder_quantity' => $this->suggested_reorder_quantity,
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

        static::creating(function ($item) {
            // Generate SKU if not provided
            if (empty($item->sku)) {
                $item->sku = self::generateSku($item->category, $item->name);
            }

            // Set initial average cost
            if ($item->unit_cost > 0 && empty($item->average_cost)) {
                $item->average_cost = $item->unit_cost;
            }

            // Set initial total value
            $item->total_value = $item->current_stock * ($item->average_cost ?: $item->unit_cost);
        });

        static::updating(function ($item) {
            // Recalculate total value if stock or cost changed
            if ($item->isDirty(['current_stock', 'average_cost', 'unit_cost'])) {
                $cost = $item->average_cost ?: $item->unit_cost;
                $item->total_value = $item->current_stock * $cost;
            }

            // Check if we need to create alerts
            if ($item->isDirty('current_stock')) {
                $item->checkAlerts();
            }

            // Check expiry
            if ($item->isDirty('expiry_date') && $item->expiry_date) {
                $item->checkAlerts();
            }
        });

        static::deleting(function ($item) {
            // Check if there are any pending orders using this item
            if ($item->orderItems()->whereHas('order', function ($q) {
                $q->whereIn('status', ['pending', 'processing']);
            })->exists()) {
                throw new \Exception('Cannot delete inventory item with pending orders.');
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
