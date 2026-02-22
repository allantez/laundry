<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'service_item_id',
        'branch_id',
        'name',
        'description',
        'sku',
        'category',
        'item_type',
        'fabric_type',
        'color',
        'size',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'subtotal',
        'total',
        'add_ons',
        'modifiers',
        'add_ons_total',
        'customer_notes',
        'staff_notes',
        'special_instructions',
        'status',
        'status_updated_at',
        'status_updated_by',
        'assigned_to',
        'started_at',
        'completed_at',
        'requires_inspection',
        'inspected',
        'inspected_by',
        'inspected_at',
        'inspection_notes',
        'track_inventory',
        'inventory_item_id',
        'inventory_quantity_used',
        'inventory_deducted',
        'is_urgent',
        'is_express',
        'is_insured',
        'is_flagged',
        'flag_reason',
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
            'add_ons' => 'json',
            'modifiers' => 'json',
            'special_instructions' => 'json',
            'metadata' => 'json',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'add_ons_total' => 'decimal:2',
            'inventory_quantity_used' => 'decimal:2',
            'status_updated_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'inspected_at' => 'datetime',
            'is_urgent' => 'boolean',
            'is_express' => 'boolean',
            'is_insured' => 'boolean',
            'is_flagged' => 'boolean',
            'requires_inspection' => 'boolean',
            'inspected' => 'boolean',
            'track_inventory' => 'boolean',
            'inventory_deducted' => 'boolean',
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
        'quantity' => 1,
        'status' => 'pending',
        'is_urgent' => false,
        'is_express' => false,
        'is_insured' => false,
        'is_flagged' => false,
        'requires_inspection' => false,
        'inspected' => false,
        'track_inventory' => false,
        'inventory_deducted' => false,
        'discount_percentage' => 0,
        'discount_amount' => 0,
        'tax_rate' => 0,
        'tax_amount' => 0,
        'add_ons_total' => 0,
    ];

    // =========================================================================
    // STATUS CONSTANTS
    // =========================================================================

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the order that this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the service associated with this item.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the service item associated with this item.
     */
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    /**
     * Get the branch that this item belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who last updated the status.
     */
    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    /**
     * Get the staff assigned to this item.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who inspected this item.
     */
    public function inspectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    /**
     * Get the inventory item used for this order item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include items with a specific status.
     */
    public function scopeWhereStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending items.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include processing items.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope a query to only include completed items.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include cancelled items.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by item type.
     */
    public function scopeOfType($query, string $itemType)
    {
        return $query->where('item_type', $itemType);
    }

    /**
     * Scope a query to filter by assigned staff.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include items requiring inspection.
     */
    public function scopeRequiresInspection($query)
    {
        return $query->where('requires_inspection', true)
            ->where('inspected', false);
    }

    /**
     * Scope a query to only include inspected items.
     */
    public function scopeInspected($query)
    {
        return $query->where('inspected', true);
    }

    /**
     * Scope a query to only include urgent items.
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Scope a query to only include express items.
     */
    public function scopeExpress($query)
    {
        return $query->where('is_express', true);
    }

    /**
     * Scope a query to only include flagged items.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the item status with color and icon.
     */
    public function getStatusInfoAttribute(): array
    {
        return match($this->status) {
            self::STATUS_PENDING => [
                'label' => 'Pending',
                'color' => 'yellow',
                'icon' => 'fa-clock',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::STATUS_PROCESSING => [
                'label' => 'Processing',
                'color' => 'blue',
                'icon' => 'fa-spinner',
                'badge' => 'bg-blue-100 text-blue-800',
            ],
            self::STATUS_COMPLETED => [
                'label' => 'Completed',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::STATUS_CANCELLED => [
                'label' => 'Cancelled',
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
     * Get the full display name with details.
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [$this->name];

        if ($this->size) {
            $parts[] = "Size: {$this->size}";
        }

        if ($this->color) {
            $parts[] = $this->color;
        }

        if ($this->fabric_type) {
            $parts[] = ucfirst($this->fabric_type);
        }

        return implode(' - ', $parts);
    }

    /**
     * Get the unit price with currency.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return 'KES ' . number_format($this->unit_price, 2);
    }

    /**
     * Get the total with currency.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'KES ' . number_format($this->total, 2);
    }

    /**
     * Check if item has add-ons.
     */
    public function getHasAddOnsAttribute(): bool
    {
        return !empty($this->add_ons);
    }

    /**
     * Get add-ons as formatted list.
     */
    public function getFormattedAddOnsAttribute(): array
    {
        if (!$this->add_ons) {
            return [];
        }

        return collect($this->add_ons)->map(function ($addOn) {
            if (is_string($addOn)) {
                return ['name' => $addOn, 'price' => 0];
            }
            return $addOn;
        })->toArray();
    }

    /**
     * Get processing time in minutes.
     */
    public function getProcessingTimeAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Calculate item totals.
     */
    public function calculateTotals(): self
    {
        // Calculate subtotal (unit price * quantity)
        $this->subtotal = $this->unit_price * $this->quantity;

        // Add add-ons total
        $this->subtotal += $this->add_ons_total;

        // Calculate discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = ($this->subtotal * $this->discount_percentage) / 100;
        }

        // Calculate taxable amount after discount
        $taxableAmount = $this->subtotal - $this->discount_amount;

        // Calculate tax
        if ($this->tax_rate > 0) {
            $this->tax_amount = ($taxableAmount * $this->tax_rate) / 100;
        }

        // Calculate total
        $this->total = $taxableAmount + $this->tax_amount;

        $this->save();

        // Update parent order totals
        $this->order->updateTotals();

        return $this;
    }

    /**
     * Update item status.
     */
    public function updateStatus(string $newStatus, ?string $notes = null): self
    {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) {
            return $this;
        }

        $this->status = $newStatus;
        $this->status_updated_at = now();
        $this->status_updated_by = auth()->id();

        if ($newStatus === self::STATUS_PROCESSING && !$this->started_at) {
            $this->started_at = now();
        }

        if ($newStatus === self::STATUS_COMPLETED) {
            $this->completed_at = now();

            // Deduct inventory if tracking
            if ($this->track_inventory && !$this->inventory_deducted) {
                $this->deductInventory();
            }
        }

        $this->save();

        // Add to order metadata about item status change
        $this->order->addToStatusHistory([
            'item_id' => $this->id,
            'item_name' => $this->name,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        return $this;
    }

    /**
     * Assign item to staff.
     */
    public function assignTo(User $staff): self
    {
        $this->assigned_to = $staff->id;
        $this->save();

        return $this;
    }

    /**
     * Mark item as inspected.
     */
    public function markInspected(User $inspector, ?string $notes = null): self
    {
        $this->inspected = true;
        $this->inspected_by = $inspector->id;
        $this->inspected_at = now();
        $this->inspection_notes = $notes;
        $this->save();

        return $this;
    }

    /**
     * Deduct inventory for this item.
     */
    public function deductInventory(): self
    {
        if (!$this->track_inventory || !$this->inventory_item_id || !$this->inventory_quantity_used) {
            return $this;
        }

        $inventoryItem = $this->inventoryItem;

        if ($inventoryItem && $inventoryItem->current_stock >= $this->inventory_quantity_used) {
            $inventoryItem->decrement('current_stock', $this->inventory_quantity_used);
            $this->inventory_deducted = true;
            $this->save();

            // Create inventory movement record
            // This would go to inventory_stock_movements table
        }

        return $this;
    }

    /**
     * Add a note to the item.
     */
    public function addNote(string $note, bool $isStaff = true): self
    {
        if ($isStaff) {
            $this->staff_notes = $this->staff_notes
                ? $this->staff_notes . "\n\n[" . now() . "] " . $note
                : "[" . now() . "] " . $note;
        } else {
            $this->customer_notes = $this->customer_notes
                ? $this->customer_notes . "\n\n[" . now() . "] " . $note
                : "[" . now() . "] " . $note;
        }

        $this->save();

        return $this;
    }

    /**
     * Add an add-on to the item.
     */
    public function addAddOn(string $name, float $price): self
    {
        $addOns = $this->add_ons ?? [];
        $addOns[] = [
            'name' => $name,
            'price' => $price,
            'added_at' => now(),
        ];

        $this->add_ons = $addOns;
        $this->add_ons_total += $price;
        $this->save();

        // Recalculate totals
        $this->calculateTotals();

        return $this;
    }

    /**
     * Remove an add-on from the item.
     */
    public function removeAddOn(int $index): self
    {
        $addOns = $this->add_ons ?? [];

        if (isset($addOns[$index])) {
            $this->add_ons_total -= $addOns[$index]['price'] ?? 0;
            unset($addOns[$index]);
            $this->add_ons = array_values($addOns); // Reindex
            $this->save();

            // Recalculate totals
            $this->calculateTotals();
        }

        return $this;
    }

    /**
     * Flag item for attention.
     */
    public function flag(string $reason): self
    {
        $this->is_flagged = true;
        $this->flag_reason = $reason;
        $this->save();

        return $this;
    }

    /**
     * Unflag item.
     */
    public function unflag(): self
    {
        $this->is_flagged = false;
        $this->flag_reason = null;
        $this->save();

        return $this;
    }

    /**
     * Duplicate this item (for split orders, etc.).
     */
    public function duplicate(int $newOrderId = null): self
    {
        $replica = $this->replicate();

        if ($newOrderId) {
            $replica->order_id = $newOrderId;
        }

        $replica->status = self::STATUS_PENDING;
        $replica->started_at = null;
        $replica->completed_at = null;
        $replica->inspected = false;
        $replica->inspected_by = null;
        $replica->inspected_at = null;
        $replica->inventory_deducted = false;
        $replica->save();

        return $replica;
    }

    /**
     * Get item summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'quantity' => $this->quantity,
            'unit_price' => $this->formatted_unit_price,
            'total' => $this->formatted_total,
            'status' => $this->status_info,
            'has_add_ons' => $this->has_add_ons,
            'assigned_to' => $this->assignedTo ? $this->assignedTo->name : null,
            'is_urgent' => $this->is_urgent,
            'is_express' => $this->is_express,
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
            // Set branch from order if not set
            if (empty($item->branch_id) && $item->order) {
                $item->branch_id = $item->order->branch_id;
            }

            // Set name from service if not set
            if (empty($item->name) && $item->service) {
                $item->name = $item->service->name;
            }

            // Set category from service if not set
            if (empty($item->category) && $item->service) {
                $item->category = $item->service->category;
            }

            // Calculate initial totals
            $item->subtotal = $item->unit_price * $item->quantity;
            $item->total = $item->subtotal - $item->discount_amount + $item->tax_amount;
        });

        static::created(function ($item) {
            // Update parent order totals
            $item->order->updateTotals();
        });

        static::updating(function ($item) {
            // Track status changes
            if ($item->isDirty('status')) {
                $item->status_updated_at = now();
                $item->status_updated_by = auth()->id();
            }
        });

        static::updated(function ($item) {
            // Update parent order totals if financial fields changed
            if ($item->isDirty(['quantity', 'unit_price', 'discount_amount', 'tax_amount', 'add_ons_total'])) {
                $item->order->updateTotals();
            }
        });

        static::deleted(function ($item) {
            // Update parent order totals
            $item->order->updateTotals();
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
