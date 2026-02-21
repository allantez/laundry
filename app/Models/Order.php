<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'invoice_number',
        'branch_id',
        'customer_id',
        'created_by',
        'updated_by',
        'assigned_to',
        'status',
        'status_updated_at',
        'status_updated_by',
        'order_type',
        'service_type',
        'payment_status',
        'order_date',
        'requested_pickup_date',
        'requested_delivery_date',
        'actual_pickup_date',
        'actual_delivery_date',
        'promised_completion_date',
        'completed_at',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'delivery_fee',
        'pickup_fee',
        'extra_charges',
        'total_amount',
        'paid_amount',
        'balance_due',
        'discount_code',
        'discount_type',
        'discount_value',
        'tax_rate',
        'tax_description',
        'delivery_address',
        'delivery_contact_name',
        'delivery_contact_phone',
        'delivery_instructions',
        'pickup_address',
        'pickup_contact_name',
        'pickup_contact_phone',
        'pickup_instructions',
        'customer_notes',
        'staff_notes',
        'special_instructions',
        'metadata',
        'tags',
        'is_urgent',
        'is_insured',
        'requires_approval',
        'is_approved',
        'approved_by',
        'approved_at',
        'is_flagged',
        'flag_reason',
        'flagged_by',
        'status_history',
        'payment_history',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'special_instructions' => 'json',
            'metadata' => 'json',
            'tags' => 'json',
            'status_history' => 'json',
            'payment_history' => 'json',
            'order_date' => 'datetime',
            'requested_pickup_date' => 'datetime',
            'requested_delivery_date' => 'datetime',
            'actual_pickup_date' => 'datetime',
            'actual_delivery_date' => 'datetime',
            'promised_completion_date' => 'datetime',
            'completed_at' => 'datetime',
            'status_updated_at' => 'datetime',
            'approved_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'pickup_fee' => 'decimal:2',
            'extra_charges' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'is_urgent' => 'boolean',
            'is_insured' => 'boolean',
            'requires_approval' => 'boolean',
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
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
        'status' => 'pending',
        'payment_status' => 'pending',
        'order_type' => 'walk_in',
        'service_type' => 'regular',
        'subtotal' => 0,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'delivery_fee' => 0,
        'pickup_fee' => 0,
        'extra_charges' => 0,
        'total_amount' => 0,
        'paid_amount' => 0,
        'balance_due' => 0,
        'is_urgent' => false,
        'is_insured' => false,
        'requires_approval' => false,
        'is_approved' => false,
        'is_flagged' => false,
    ];

    // =========================================================================
    // STATUS CONSTANTS
    // =========================================================================

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY = 'ready';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_PARTIALLY_PAID = 'partially_paid';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    const ORDER_TYPE_PICKUP = 'pickup';
    const ORDER_TYPE_DELIVERY = 'delivery';
    const ORDER_TYPE_WALK_IN = 'walk_in';

    const SERVICE_TYPE_REGULAR = 'regular';
    const SERVICE_TYPE_EXPRESS = 'express';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this order belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the customer that placed this order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created this order.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this order.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the staff assigned to this order.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who last updated the status.
     */
    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    /**
     * Get the user who approved this order.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who flagged this order.
     */
    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    /**
     * Get all items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all payments for this order.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the feedback for this order.
     */
    public function feedback(): HasOne
    {
        return $this->hasOne(CustomerFeedback::class);
    }

    /**
     * Get all status logs for this order.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeWhereStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include processing orders.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope a query to only include ready orders.
     */
    public function scopeReady($query)
    {
        return $query->where('status', self::STATUS_READY);
    }

    /**
     * Scope a query to only include delivered orders.
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include orders with a specific payment status.
     */
    public function scopeWherePaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    /**
     * Scope a query to only include unpaid orders.
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PARTIALLY_PAID]);
    }

    /**
     * Scope a query to only include orders that are overdue.
     */
    public function scopeOverdue($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING])
            ->where('promised_completion_date', '<', now())
            ->where('promised_completion_date', '!=', null);
    }

    /**
     * Scope a query to only include orders for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('order_date', today());
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by customer.
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to filter by assigned staff.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include urgent orders.
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Scope a query to only include flagged orders.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope a query to search orders.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
                ->orWhere('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
        });
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the order status with color and icon.
     */
    public function getStatusInfoAttribute(): array
    {
        return match ($this->status) {
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
            self::STATUS_READY => [
                'label' => 'Ready',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::STATUS_DELIVERED => [
                'label' => 'Delivered',
                'color' => 'purple',
                'icon' => 'fa-truck',
                'badge' => 'bg-purple-100 text-purple-800',
            ],
            self::STATUS_COMPLETED => [
                'label' => 'Completed',
                'color' => 'green',
                'icon' => 'fa-check-double',
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
     * Get payment status with color and icon.
     */
    public function getPaymentStatusInfoAttribute(): array
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_PAID => [
                'label' => 'Paid',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::PAYMENT_STATUS_PARTIALLY_PAID => [
                'label' => 'Partially Paid',
                'color' => 'yellow',
                'icon' => 'fa-exclamation-circle',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::PAYMENT_STATUS_PENDING => [
                'label' => 'Pending',
                'color' => 'red',
                'icon' => 'fa-clock',
                'badge' => 'bg-red-100 text-red-800',
            ],
            self::PAYMENT_STATUS_REFUNDED => [
                'label' => 'Refunded',
                'color' => 'purple',
                'icon' => 'fa-undo',
                'badge' => 'bg-purple-100 text-purple-800',
            ],
            default => [
                'label' => ucfirst($this->payment_status),
                'color' => 'gray',
                'icon' => 'fa-question',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get order type with icon.
     */
    public function getOrderTypeInfoAttribute(): array
    {
        return match ($this->order_type) {
            self::ORDER_TYPE_PICKUP => [
                'label' => 'Pickup',
                'icon' => 'fa-store',
            ],
            self::ORDER_TYPE_DELIVERY => [
                'label' => 'Delivery',
                'icon' => 'fa-truck',
            ],
            self::ORDER_TYPE_WALK_IN => [
                'label' => 'Walk-in',
                'icon' => 'fa-user',
            ],
            default => [
                'label' => ucfirst($this->order_type),
                'icon' => 'fa-tag',
            ],
        };
    }

    /**
     * Get service type with icon.
     */
    public function getServiceTypeInfoAttribute(): array
    {
        return match ($this->service_type) {
            self::SERVICE_TYPE_EXPRESS => [
                'label' => 'Express',
                'icon' => 'fa-bolt',
                'multiplier' => 1.5,
            ],
            default => [
                'label' => 'Regular',
                'icon' => 'fa-clock',
                'multiplier' => 1.0,
            ],
        };
    }

    /**
     * Check if order is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING])
            && $this->promised_completion_date
            && $this->promised_completion_date->isPast();
    }

    /**
     * Get days since order was created.
     */
    public function getDaysSinceOrderedAttribute(): int
    {
        return $this->order_date->diffInDays(now());
    }

    /**
     * Get formatted total with currency.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'KES ' . number_format($this->total_amount, 2);
    }

    /**
     * Get formatted balance with currency.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'KES ' . number_format($this->balance_due, 2);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastOrder = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "ORD-{$year}{$month}-{$newNumber}";
    }

    /**
     * Generate a unique invoice number.
     */
    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $this->invoice_number = "INV-{$year}{$month}-" . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->save();

        return $this->invoice_number;
    }

    /**
     * Update order totals.
     */
    public function updateTotals(): self
    {
        // Calculate subtotal from items
        $this->subtotal = $this->items()->sum('subtotal');

        // Calculate total
        $this->total_amount = $this->subtotal
            + $this->delivery_fee
            + $this->pickup_fee
            + $this->extra_charges
            + $this->tax_amount
            - $this->discount_amount;

        // Calculate balance
        $this->balance_due = $this->total_amount - $this->paid_amount;

        $this->save();

        return $this;
    }

    /**
     * Update payment status based on payments.
     */
    public function updatePaymentStatus(): self
    {
        $totalPaid = $this->payments()->sum('amount');
        $this->paid_amount = $totalPaid;
        $this->balance_due = $this->total_amount - $totalPaid;

        if ($this->balance_due <= 0) {
            $this->payment_status = self::PAYMENT_STATUS_PAID;
        } elseif ($totalPaid > 0) {
            $this->payment_status = self::PAYMENT_STATUS_PARTIALLY_PAID;
        } else {
            $this->payment_status = self::PAYMENT_STATUS_PENDING;
        }

        $this->save();

        // Add to payment history
        $this->addToPaymentHistory([
            'action' => 'payment_status_updated',
            'old_status' => $this->getOriginal('payment_status'),
            'new_status' => $this->payment_status,
            'total_paid' => $totalPaid,
            'balance' => $this->balance_due,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return $this;
    }

    /**
     * Update order status.
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

        if ($newStatus === self::STATUS_COMPLETED) {
            $this->completed_at = now();
        }

        if ($newStatus === self::STATUS_DELIVERED) {
            $this->actual_delivery_date = now();
        }

        $this->save();

        // Create status log
        $this->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        // Add to status history
        $this->addToStatusHistory([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        // Update customer loyalty points if completed
        if ($newStatus === self::STATUS_COMPLETED && $this->customer) {
            $pointsEarned = floor($this->total_amount / 100); // 1 point per 100 spent
            $this->customer->addLoyaltyPoints($pointsEarned);
            $this->customer->updateOrderStatistics($this);
        }

        return $this;
    }

    /**
     * Add to status history.
     */
    protected function addToStatusHistory(array $entry): self
    {
        $history = $this->status_history ?? [];
        $history[] = $entry;
        $this->status_history = $history;
        $this->saveQuietly();

        return $this;
    }

    /**
     * Add to payment history.
     */
    protected function addToPaymentHistory(array $entry): self
    {
        $history = $this->payment_history ?? [];
        $history[] = $entry;
        $this->payment_history = $history;
        $this->saveQuietly();

        return $this;
    }

    /**
     * Assign order to staff.
     */
    public function assignTo(User $staff): self
    {
        $this->assigned_to = $staff->id;
        $this->save();

        return $this;
    }

    /**
     * Mark order as urgent.
     */
    public function markUrgent(string $reason = null): self
    {
        $this->is_urgent = true;
        $this->metadata = array_merge($this->metadata ?? [], ['urgent_reason' => $reason]);
        $this->save();

        return $this;
    }

    /**
     * Flag order for attention.
     */
    public function flag(string $reason, User $flaggedBy): self
    {
        $this->is_flagged = true;
        $this->flag_reason = $reason;
        $this->flagged_by = $flaggedBy->id;
        $this->save();

        return $this;
    }

    /**
     * Unflag order.
     */
    public function unflag(): self
    {
        $this->is_flagged = false;
        $this->flag_reason = null;
        $this->flagged_by = null;
        $this->save();

        return $this;
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
        ]);
    }

    /**
     * Cancel the order.
     */
    public function cancel(string $reason = null): self
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Order cannot be cancelled in its current status.');
        }

        $this->updateStatus(self::STATUS_CANCELLED, $reason);

        return $this;
    }

    /**
     * Get order summary.
     */
    public function getSummary(): array
    {
        return [
            'order_number' => $this->order_number,
            'customer' => $this->customer ? $this->customer->full_name : 'Walk-in Customer',
            'status' => $this->status_info,
            'payment_status' => $this->payment_status_info,
            'items_count' => $this->items()->count(),
            'total' => $this->formatted_total,
            'balance' => $this->formatted_balance,
            'order_date' => $this->order_date->format('Y-m-d H:i'),
            'is_overdue' => $this->is_overdue,
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

        static::creating(function ($order) {
            $order->uuid = (string) Str::uuid();
        });

        static::creating(function ($order) {
            // Generate order number
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }

            // Set order date if not set
            if (empty($order->order_date)) {
                $order->order_date = now();
            }

            // Set created_by if not set
            if (empty($order->created_by) && auth()->check()) {
                $order->created_by = auth()->id();
            }

            // Initialize status history
            $order->status_history = [];
            $order->addToStatusHistory([
                'old_status' => null,
                'new_status' => $order->status,
                'notes' => 'Order created',
                'changed_by' => $order->created_by,
                'changed_at' => now(),
            ]);
        });

        static::updating(function ($order) {
            $order->updated_by = auth()->id();
        });

        static::deleting(function ($order) {
            // Check if order has payments
            if ($order->payments()->exists()) {
                throw new \Exception('Cannot delete order with existing payments.');
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
