<?php
// app/Models/InventoryStockMovement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class InventoryStockMovement extends Model
{
    use HasFactory, SoftDeletes,;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory_stock_movements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_item_id',
        'branch_id',
        'created_by',
        'approved_by',
        'movement_type',
        'direction',
        'quantity',
        'unit_cost',
        'total_cost',
        'previous_stock',
        'new_stock',
        'change_amount',
        'reference_type',
        'reference_id',
        'from_branch_id',
        'to_branch_id',
        'reason',
        'notes',
        'status',
        'approved_at',
        'cancelled_at',
        'cancellation_reason',
        'document_number',
        'document_path',
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
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'previous_stock' => 'decimal:2',
            'new_stock' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
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
        'status' => 'approved',
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    const MOVEMENT_TYPE_PURCHASE = 'purchase';
    const MOVEMENT_TYPE_SALE = 'sale';
    const MOVEMENT_TYPE_RETURN = 'return';
    const MOVEMENT_TYPE_ADJUSTMENT = 'adjustment';
    const MOVEMENT_TYPE_TRANSFER = 'transfer';
    const MOVEMENT_TYPE_WASTE = 'waste';
    const MOVEMENT_TYPE_RETURN_TO_SUPPLIER = 'return_to_supplier';
    const MOVEMENT_TYPE_INITIAL_STOCK = 'initial_stock';

    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the inventory item for this movement.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the branch where this movement occurred.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created this movement.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this movement.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the source branch (for transfers).
     */
    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    /**
     * Get the destination branch (for transfers).
     */
    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    /**
     * Get the referenced model (polymorphic).
     */
    public function reference()
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include movements of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('movement_type', $type);
    }

    /**
     * Scope a query to only include purchase movements.
     */
    public function scopePurchases($query)
    {
        return $query->where('movement_type', self::MOVEMENT_TYPE_PURCHASE);
    }

    /**
     * Scope a query to only include sale movements.
     */
    public function scopeSales($query)
    {
        return $query->where('movement_type', self::MOVEMENT_TYPE_SALE);
    }

    /**
     * Scope a query to only include adjustments.
     */
    public function scopeAdjustments($query)
    {
        return $query->where('movement_type', self::MOVEMENT_TYPE_ADJUSTMENT);
    }

    /**
     * Scope a query to only include transfers.
     */
    public function scopeTransfers($query)
    {
        return $query->where('movement_type', self::MOVEMENT_TYPE_TRANSFER);
    }

    /**
     * Scope a query to only include waste movements.
     */
    public function scopeWaste($query)
    {
        return $query->where('movement_type', self::MOVEMENT_TYPE_WASTE);
    }

    /**
     * Scope a query to only include inbound movements.
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', self::DIRECTION_IN);
    }

    /**
     * Scope a query to only include outbound movements.
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', self::DIRECTION_OUT);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeWhereStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending movements.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved movements.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by inventory item.
     */
    public function scopeForItem($query, $itemId)
    {
        return $query->where('inventory_item_id', $itemId);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by reference.
     */
    public function scopeForReference($query, string $referenceType, $referenceId)
    {
        return $query->where('reference_type', $referenceType)
            ->where('reference_id', $referenceId);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get movement type label with color and icon.
     */
    public function getMovementTypeInfoAttribute(): array
    {
        return match($this->movement_type) {
            self::MOVEMENT_TYPE_PURCHASE => [
                'label' => 'Purchase',
                'color' => 'green',
                'icon' => 'fa-shopping-cart',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::MOVEMENT_TYPE_SALE => [
                'label' => 'Sale/Usage',
                'color' => 'blue',
                'icon' => 'fa-shopping-bag',
                'badge' => 'bg-blue-100 text-blue-800',
            ],
            self::MOVEMENT_TYPE_RETURN => [
                'label' => 'Customer Return',
                'color' => 'purple',
                'icon' => 'fa-undo',
                'badge' => 'bg-purple-100 text-purple-800',
            ],
            self::MOVEMENT_TYPE_ADJUSTMENT => [
                'label' => 'Adjustment',
                'color' => 'orange',
                'icon' => 'fa-sliders-h',
                'badge' => 'bg-orange-100 text-orange-800',
            ],
            self::MOVEMENT_TYPE_TRANSFER => [
                'label' => 'Branch Transfer',
                'color' => 'yellow',
                'icon' => 'fa-exchange-alt',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::MOVEMENT_TYPE_WASTE => [
                'label' => 'Waste/Damage',
                'color' => 'red',
                'icon' => 'fa-trash-alt',
                'badge' => 'bg-red-100 text-red-800',
            ],
            self::MOVEMENT_TYPE_RETURN_TO_SUPPLIER => [
                'label' => 'Return to Supplier',
                'color' => 'gray',
                'icon' => 'fa-truck',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
            self::MOVEMENT_TYPE_INITIAL_STOCK => [
                'label' => 'Initial Stock',
                'color' => 'teal',
                'icon' => 'fa-flag',
                'badge' => 'bg-teal-100 text-teal-800',
            ],
            default => [
                'label' => ucfirst(str_replace('_', ' ', $this->movement_type)),
                'color' => 'gray',
                'icon' => 'fa-question',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get direction with color.
     */
    public function getDirectionInfoAttribute(): array
    {
        return match($this->direction) {
            self::DIRECTION_IN => [
                'label' => 'In',
                'color' => 'green',
                'icon' => 'fa-arrow-down',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::DIRECTION_OUT => [
                'label' => 'Out',
                'color' => 'red',
                'icon' => 'fa-arrow-up',
                'badge' => 'bg-red-100 text-red-800',
            ],
            default => [
                'label' => ucfirst($this->direction),
                'color' => 'gray',
                'icon' => 'fa-arrow-right',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get status with color.
     */
    public function getStatusInfoAttribute(): array
    {
        return match($this->status) {
            self::STATUS_APPROVED => [
                'label' => 'Approved',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::STATUS_PENDING => [
                'label' => 'Pending',
                'color' => 'yellow',
                'icon' => 'fa-clock',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::STATUS_CANCELLED => [
                'label' => 'Cancelled',
                'color' => 'gray',
                'icon' => 'fa-ban',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
            self::STATUS_REJECTED => [
                'label' => 'Rejected',
                'color' => 'red',
                'icon' => 'fa-times-circle',
                'badge' => 'bg-red-100 text-red-800',
            ],
            default => [
                'label' => ucfirst($this->status),
                'color' => 'gray',
                'icon' => 'fa-question',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get formatted quantity with direction indicator.
     */
    public function getFormattedQuantityAttribute(): string
    {
        $sign = $this->direction === self::DIRECTION_IN ? '+' : '-';
        $number = number_format(abs($this->quantity), 2);
        $unit = $this->inventoryItem?->unit_type ?? 'units';

        return "{$sign} {$number} {$unit}";
    }

    /**
     * Get formatted total cost.
     */
    public function getFormattedTotalCostAttribute(): string
    {
        if (!$this->total_cost) {
            return 'N/A';
        }

        return 'KES ' . number_format($this->total_cost, 2);
    }

    /**
     * Get formatted unit cost.
     */
    public function getFormattedUnitCostAttribute(): string
    {
        if (!$this->unit_cost) {
            return 'N/A';
        }

        return 'KES ' . number_format($this->unit_cost, 2);
    }

    /**
     * Get the reference description.
     */
    public function getReferenceDescriptionAttribute(): string
    {
        if (!$this->reference_type || !$this->reference_id) {
            return 'Manual Entry';
        }

        return match($this->reference_type) {
            'order' => "Order #{$this->reference_id}",
            'purchase' => "Purchase #{$this->reference_id}",
            'return' => "Return #{$this->reference_id}",
            'adjustment' => "Adjustment #{$this->reference_id}",
            default => ucfirst($this->reference_type) . " #{$this->reference_id}",
        };
    }

    /**
     * Get stock change summary.
     */
    public function getStockChangeAttribute(): string
    {
        if ($this->previous_stock === null || $this->new_stock === null) {
            return 'N/A';
        }

        return number_format($this->previous_stock, 2) . ' → ' . number_format($this->new_stock, 2);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Create a purchase movement (stock in).
     */
    public static function createPurchase(
        InventoryItem $item,
        float $quantity,
        float $unitCost,
        ?string $documentNumber = null,
        ?array $metadata = [],
        ?User $user = null
    ): self {
        $previousStock = $item->current_stock;
        $newStock = $previousStock + $quantity;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_PURCHASE,
            'direction' => self::DIRECTION_IN,
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'total_cost' => $quantity * $unitCost,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'change_amount' => $quantity,
            'document_number' => $documentNumber,
            'metadata' => $metadata,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $movement->save();

        return $movement;
    }

    /**
     * Create a sale/usage movement (stock out).
     */
    public static function createSale(
        InventoryItem $item,
        float $quantity,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?array $metadata = [],
        ?User $user = null
    ): self {
        $previousStock = $item->current_stock;
        $newStock = $previousStock - $quantity;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_SALE,
            'direction' => self::DIRECTION_OUT,
            'quantity' => $quantity,
            'unit_cost' => $item->average_cost,
            'total_cost' => $quantity * $item->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'change_amount' => -$quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'metadata' => $metadata,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $movement->save();

        return $movement;
    }

    /**
     * Create an adjustment movement.
     */
    public static function createAdjustment(
        InventoryItem $item,
        float $newQuantity,
        string $reason,
        ?array $metadata = [],
        ?User $user = null,
        bool $requireApproval = false
    ): self {
        $previousStock = $item->current_stock;
        $changeAmount = $newQuantity - $previousStock;
        $direction = $changeAmount > 0 ? self::DIRECTION_IN : self::DIRECTION_OUT;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_ADJUSTMENT,
            'direction' => $direction,
            'quantity' => abs($changeAmount),
            'unit_cost' => $item->average_cost,
            'total_cost' => abs($changeAmount) * $item->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newQuantity,
            'change_amount' => $changeAmount,
            'reason' => $reason,
            'metadata' => $metadata,
            'status' => $requireApproval ? self::STATUS_PENDING : self::STATUS_APPROVED,
        ]);

        $movement->save();

        if (!$requireApproval) {
            $movement->markAsApproved($user);
        }

        return $movement;
    }

    /**
     * Create a transfer movement (stock out from source).
     */
    public static function createTransferOut(
        InventoryItem $item,
        float $quantity,
        Branch $toBranch,
        string $reason = null,
        ?User $user = null
    ): self {
        $previousStock = $item->current_stock;
        $newStock = $previousStock - $quantity;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_TRANSFER,
            'direction' => self::DIRECTION_OUT,
            'quantity' => $quantity,
            'unit_cost' => $item->average_cost,
            'total_cost' => $quantity * $item->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'change_amount' => -$quantity,
            'to_branch_id' => $toBranch->id,
            'reason' => $reason,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $movement->save();

        return $movement;
    }

    /**
     * Create a transfer movement (stock in to destination).
     */
    public static function createTransferIn(
        InventoryItem $item,
        float $quantity,
        Branch $fromBranch,
        string $reason = null,
        ?User $user = null,
        ?string $referenceNumber = null
    ): self {
        $previousStock = $item->current_stock;
        $newStock = $previousStock + $quantity;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_TRANSFER,
            'direction' => self::DIRECTION_IN,
            'quantity' => $quantity,
            'unit_cost' => $item->average_cost,
            'total_cost' => $quantity * $item->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'change_amount' => $quantity,
            'from_branch_id' => $fromBranch->id,
            'reason' => $reason,
            'document_number' => $referenceNumber,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $movement->save();

        return $movement;
    }

    /**
     * Create a waste movement.
     */
    public static function createWaste(
        InventoryItem $item,
        float $quantity,
        string $reason,
        ?array $metadata = [],
        ?User $user = null
    ): self {
        $previousStock = $item->current_stock;
        $newStock = $previousStock - $quantity;

        $movement = new self([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'created_by' => $user?->id,
            'movement_type' => self::MOVEMENT_TYPE_WASTE,
            'direction' => self::DIRECTION_OUT,
            'quantity' => $quantity,
            'unit_cost' => $item->average_cost,
            'total_cost' => $quantity * $item->average_cost,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'change_amount' => -$quantity,
            'reason' => $reason,
            'metadata' => $metadata,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $movement->save();

        return $movement;
    }

    /**
     * Mark movement as approved.
     */
    public function markAsApproved(?User $user = null): self
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $user?->id;
        $this->approved_at = now();
        $this->save();

        // Apply stock changes to inventory item
        $item = $this->inventoryItem;

        if ($this->movement_type === self::MOVEMENT_TYPE_ADJUSTMENT) {
            $item->current_stock = $this->new_stock;
        } elseif ($this->direction === self::DIRECTION_IN) {
            $item->current_stock += $this->quantity;
        } else {
            $item->current_stock -= $this->quantity;
        }

        $item->save();

        return $this;
    }

    /**
     * Mark movement as cancelled.
     */
    public function markAsCancelled(string $reason, ?User $user = null): self
    {
        $this->status = self::STATUS_CANCELLED;
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->save();

        return $this;
    }

    /**
     * Mark movement as rejected.
     */
    public function markAsRejected(string $reason, ?User $user = null): self
    {
        $this->status = self::STATUS_REJECTED;
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->save();

        return $this;
    }

    /**
     * Check if movement is inbound.
     */
    public function isInbound(): bool
    {
        return $this->direction === self::DIRECTION_IN;
    }

    /**
     * Check if movement is outbound.
     */
    public function isOutbound(): bool
    {
        return $this->direction === self::DIRECTION_OUT;
    }

    /**
     * Get movement summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'item' => $this->inventoryItem?->name,
            'type' => $this->movement_type_info,
            'direction' => $this->direction_info,
            'quantity' => $this->formatted_quantity,
            'unit_cost' => $this->formatted_unit_cost,
            'total_cost' => $this->formatted_total_cost,
            'stock_change' => $this->stock_change,
            'reference' => $this->reference_description,
            'reason' => $this->reason,
            'status' => $this->status_info,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'created_by' => $this->createdBy?->name ?? 'System',
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

        static::creating(function ($movement) {
            // Calculate total cost if not provided
            if ($movement->quantity && $movement->unit_cost && !$movement->total_cost) {
                $movement->total_cost = $movement->quantity * $movement->unit_cost;
            }

            // Set branch from inventory item if not set
            if (empty($movement->branch_id) && $movement->inventory_item_id) {
                $item = InventoryItem::find($movement->inventory_item_id);
                $movement->branch_id = $item?->branch_id;
            }
        });

        static::created(function ($movement) {
            // If movement is approved immediately, update stock
            if ($movement->status === self::STATUS_APPROVED) {
                $item = $movement->inventoryItem;

                if ($movement->movement_type === self::MOVEMENT_TYPE_ADJUSTMENT) {
                    $item->current_stock = $movement->new_stock;
                } elseif ($movement->direction === self::DIRECTION_IN) {
                    $item->current_stock += $movement->quantity;
                } else {
                    $item->current_stock -= $movement->quantity;
                }

                $item->save();
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
