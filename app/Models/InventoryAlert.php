<?php
// app/Models/InventoryAlert.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class InventoryAlert extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory_alerts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_item_id',
        'branch_id',
        'type',
        'severity',
        'title',
        'message',
        'details',
        'threshold_value',
        'current_value',
        'difference',
        'expiry_date',
        'days_until_expiry',
        'current_stock',
        'minimum_stock',
        'reorder_point',
        'recorded_temperature',
        'min_temperature',
        'max_temperature',
        'status',
        'acknowledged_at',
        'acknowledged_by',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'resolution_action',
        'dismissed_at',
        'dismissed_by',
        'dismissal_reason',
        'notifications_sent',
        'last_notified_at',
        'notification_count',
        'assigned_to',
        'assigned_at',
        'is_escalated',
        'escalated_at',
        'escalated_to',
        'comments',
        'comment_history',
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
            'details' => 'json',
            'notifications_sent' => 'json',
            'comment_history' => 'json',
            'metadata' => 'json',
            'threshold_value' => 'decimal:2',
            'current_value' => 'decimal:2',
            'difference' => 'decimal:2',
            'current_stock' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'reorder_point' => 'decimal:2',
            'recorded_temperature' => 'decimal:2',
            'min_temperature' => 'decimal:2',
            'max_temperature' => 'decimal:2',
            'days_until_expiry' => 'integer',
            'notification_count' => 'integer',
            'expiry_date' => 'date',
            'acknowledged_at' => 'datetime',
            'resolved_at' => 'datetime',
            'dismissed_at' => 'datetime',
            'last_notified_at' => 'datetime',
            'assigned_at' => 'datetime',
            'escalated_at' => 'datetime',
            'is_escalated' => 'boolean',
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
        'severity' => 'warning',
        'status' => 'active',
        'notification_count' => 0,
        'is_escalated' => false,
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    const TYPE_LOW_STOCK = 'low_stock';
    const TYPE_OUT_OF_STOCK = 'out_of_stock';
    const TYPE_EXPIRY = 'expiry';
    const TYPE_EXPIRED = 'expired';
    const TYPE_OVERSTOCK = 'overstock';
    const TYPE_REORDER_NEEDED = 'reorder_needed';
    const TYPE_INSPECTION_NEEDED = 'inspection_needed';
    const TYPE_QUALITY_ISSUE = 'quality_issue';
    const TYPE_TEMPERATURE_ALERT = 'temperature_alert';
    const TYPE_DAMAGED = 'damaged';
    const TYPE_THEFT_SUSPECTED = 'theft_suspected';
    const TYPE_DISCREPANCY = 'discrepancy';

    const SEVERITY_INFO = 'info';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_CRITICAL = 'critical';
    const SEVERITY_EMERGENCY = 'emergency';

    const STATUS_ACTIVE = 'active';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    const RESOLUTION_ACTION_REORDERED = 'reordered';
    const RESOLUTION_ACTION_ADJUSTED = 'adjusted';
    const RESOLUTION_ACTION_INSPECTED = 'inspected';
    const RESOLUTION_ACTION_DISCARDED = 'discarded';
    const RESOLUTION_ACTION_TRANSFERRED = 'transferred';
    const RESOLUTION_ACTION_OTHER = 'other';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the inventory item for this alert.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the branch for this alert.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who acknowledged this alert.
     */
    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /**
     * Get the user who resolved this alert.
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Get the user who dismissed this alert.
     */
    public function dismissedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dismissed_by');
    }

    /**
     * Get the user assigned to this alert.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user this alert is escalated to.
     */
    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include alerts of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include low stock alerts.
     */
    public function scopeLowStock($query)
    {
        return $query->where('type', self::TYPE_LOW_STOCK);
    }

    /**
     * Scope a query to only include expiry alerts.
     */
    public function scopeExpiry($query)
    {
        return $query->whereIn('type', [self::TYPE_EXPIRY, self::TYPE_EXPIRED]);
    }

    /**
     * Scope a query to filter by severity.
     */
    public function scopeWithSeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to only include critical alerts.
     */
    public function scopeCritical($query)
    {
        return $query->whereIn('severity', [self::SEVERITY_CRITICAL, self::SEVERITY_EMERGENCY]);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeWhereStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include active alerts.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_ACKNOWLEDGED]);
    }

    /**
     * Scope a query to only include unresolved alerts.
     */
    public function scopeUnresolved($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_ACKNOWLEDGED]);
    }

    /**
     * Scope a query to only include resolved alerts.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by inventory item.
     */
    public function scopeForItem($query, $itemId)
    {
        return $query->where('inventory_item_id', $itemId);
    }

    /**
     * Scope a query to filter by assigned user.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include escalated alerts.
     */
    public function scopeEscalated($query)
    {
        return $query->where('is_escalated', true);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeCreatedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get alert type with color and icon.
     */
    public function getTypeInfoAttribute(): array
    {
        return match($this->type) {
            self::TYPE_LOW_STOCK => [
                'label' => 'Low Stock',
                'color' => 'yellow',
                'icon' => 'fa-exclamation-triangle',
            ],
            self::TYPE_OUT_OF_STOCK => [
                'label' => 'Out of Stock',
                'color' => 'red',
                'icon' => 'fa-times-circle',
            ],
            self::TYPE_EXPIRY => [
                'label' => 'Expiring Soon',
                'color' => 'orange',
                'icon' => 'fa-clock',
            ],
            self::TYPE_EXPIRED => [
                'label' => 'Expired',
                'color' => 'red',
                'icon' => 'fa-skull',
            ],
            self::TYPE_OVERSTOCK => [
                'label' => 'Overstock',
                'color' => 'blue',
                'icon' => 'fa-arrow-up',
            ],
            self::TYPE_REORDER_NEEDED => [
                'label' => 'Reorder Needed',
                'color' => 'purple',
                'icon' => 'fa-shopping-cart',
            ],
            self::TYPE_INSPECTION_NEEDED => [
                'label' => 'Inspection Needed',
                'color' => 'indigo',
                'icon' => 'fa-clipboard-check',
            ],
            self::TYPE_QUALITY_ISSUE => [
                'label' => 'Quality Issue',
                'color' => 'orange',
                'icon' => 'fa-exclamation-circle',
            ],
            self::TYPE_TEMPERATURE_ALERT => [
                'label' => 'Temperature Alert',
                'color' => 'cyan',
                'icon' => 'fa-thermometer-half',
            ],
            self::TYPE_DAMAGED => [
                'label' => 'Damaged',
                'color' => 'red',
                'icon' => 'fa-broken',
            ],
            self::TYPE_THEFT_SUSPECTED => [
                'label' => 'Theft Suspected',
                'color' => 'red',
                'icon' => 'fa-mask',
            ],
            self::TYPE_DISCREPANCY => [
                'label' => 'Discrepancy',
                'color' => 'orange',
                'icon' => 'fa-scale-balanced',
            ],
            default => [
                'label' => ucfirst(str_replace('_', ' ', $this->type)),
                'color' => 'gray',
                'icon' => 'fa-bell',
            ],
        };
    }

    /**
     * Get severity with color and icon.
     */
    public function getSeverityInfoAttribute(): array
    {
        return match($this->severity) {
            self::SEVERITY_INFO => [
                'label' => 'Info',
                'color' => 'blue',
                'icon' => 'fa-info-circle',
                'badge' => 'bg-blue-100 text-blue-800',
            ],
            self::SEVERITY_WARNING => [
                'label' => 'Warning',
                'color' => 'yellow',
                'icon' => 'fa-exclamation-triangle',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::SEVERITY_CRITICAL => [
                'label' => 'Critical',
                'color' => 'orange',
                'icon' => 'fa-exclamation-circle',
                'badge' => 'bg-orange-100 text-orange-800',
            ],
            self::SEVERITY_EMERGENCY => [
                'label' => 'Emergency',
                'color' => 'red',
                'icon' => 'fa-bell',
                'badge' => 'bg-red-100 text-red-800',
            ],
            default => [
                'label' => ucfirst($this->severity),
                'color' => 'gray',
                'icon' => 'fa-circle',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get status with color and icon.
     */
    public function getStatusInfoAttribute(): array
    {
        return match($this->status) {
            self::STATUS_ACTIVE => [
                'label' => 'Active',
                'color' => 'red',
                'icon' => 'fa-bell',
                'badge' => 'bg-red-100 text-red-800',
            ],
            self::STATUS_ACKNOWLEDGED => [
                'label' => 'Acknowledged',
                'color' => 'yellow',
                'icon' => 'fa-eye',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::STATUS_RESOLVED => [
                'label' => 'Resolved',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::STATUS_DISMISSED => [
                'label' => 'Dismissed',
                'color' => 'gray',
                'icon' => 'fa-ban',
                'badge' => 'bg-gray-100 text-gray-800',
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
     * Get formatted threshold vs current.
     */
    public function getThresholdDisplayAttribute(): string
    {
        if ($this->threshold_value === null || $this->current_value === null) {
            return 'N/A';
        }

        return number_format($this->current_value, 2) . ' / ' . number_format($this->threshold_value, 2);
    }

    /**
     * Get time since alert was created.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get age in hours (for SLA tracking).
     */
    public function getAgeInHoursAttribute(): int
    {
        return $this->created_at->diffInHours(now());
    }

    /**
     * Check if alert is overdue (based on severity).
     */
    public function getIsOverdueAttribute(): bool
    {
        $slaHours = match($this->severity) {
            self::SEVERITY_EMERGENCY => 1,
            self::SEVERITY_CRITICAL => 4,
            self::SEVERITY_WARNING => 24,
            default => 48,
        };

        return $this->status === self::STATUS_ACTIVE && $this->age_in_hours > $slaHours;
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Create a low stock alert.
     */
    public static function createLowStockAlert(InventoryItem $item, float $currentStock, float $minimumStock): self
    {
        $severity = $currentStock <= 0 ? self::SEVERITY_CRITICAL : self::SEVERITY_WARNING;

        return self::create([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'type' => $currentStock <= 0 ? self::TYPE_OUT_OF_STOCK : self::TYPE_LOW_STOCK,
            'severity' => $severity,
            'title' => $currentStock <= 0 ? "Out of Stock: {$item->name}" : "Low Stock: {$item->name}",
            'message' => $currentStock <= 0
                ? "{$item->name} is out of stock. Please reorder immediately."
                : "{$item->name} is below minimum stock level. Current: {$currentStock}, Minimum: {$minimumStock}",
            'threshold_value' => $minimumStock,
            'current_value' => $currentStock,
            'difference' => $minimumStock - $currentStock,
            'current_stock' => $currentStock,
            'minimum_stock' => $minimumStock,
            'reorder_point' => $item->reorder_point,
        ]);
    }

    /**
     * Create an expiry alert.
     */
    public static function createExpiryAlert(InventoryItem $item, InventoryStock $stock, int $daysUntilExpiry): self
    {
        $severity = $daysUntilExpiry <= 7 ? self::SEVERITY_CRITICAL :
                   ($daysUntilExpiry <= 14 ? self::SEVERITY_WARNING : self::SEVERITY_INFO);

        $type = $daysUntilExpiry <= 0 ? self::TYPE_EXPIRED : self::TYPE_EXPIRY;

        return self::create([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'type' => $type,
            'severity' => $severity,
            'title' => $daysUntilExpiry <= 0
                ? "Expired: {$item->name} (Batch: {$stock->batch_number})"
                : "Expiring Soon: {$item->name} (Batch: {$stock->batch_number})",
            'message' => $daysUntilExpiry <= 0
                ? "Stock batch {$stock->batch_number} has expired on {$stock->expiry_date->format('Y-m-d')}."
                : "Stock batch {$stock->batch_number} will expire in {$daysUntilExpiry} days on {$stock->expiry_date->format('Y-m-d')}.",
            'expiry_date' => $stock->expiry_date,
            'days_until_expiry' => $daysUntilExpiry,
            'current_stock' => $stock->quantity,
            'details' => [
                'batch_number' => $stock->batch_number,
                'lot_number' => $stock->lot_number,
                'quantity' => $stock->quantity,
                'location' => $stock->full_location,
            ],
        ]);
    }

    /**
     * Create a reorder needed alert.
     */
    public static function createReorderAlert(InventoryItem $item): self
    {
        $suggestedQuantity = $item->suggested_reorder_quantity;

        return self::create([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'type' => self::TYPE_REORDER_NEEDED,
            'severity' => self::SEVERITY_WARNING,
            'title' => "Reorder Needed: {$item->name}",
            'message' => "Stock has reached reorder point. Current: {$item->current_stock}, Reorder at: {$item->reorder_point}. Suggested order: {$suggestedQuantity} units.",
            'threshold_value' => $item->reorder_point,
            'current_value' => $item->current_stock,
            'difference' => $item->reorder_point - $item->current_stock,
            'current_stock' => $item->current_stock,
            'reorder_point' => $item->reorder_point,
            'details' => [
                'suggested_quantity' => $suggestedQuantity,
                'supplier_id' => $item->supplier_id,
                'supplier_name' => $item->supplier?->name,
            ],
        ]);
    }

    /**
     * Create a temperature alert.
     */
    public static function createTemperatureAlert(
        InventoryItem $item,
        float $recordedTemp,
        float $minTemp,
        float $maxTemp,
        ?string $location = null
    ): self {
        $isOutOfRange = $recordedTemp < $minTemp || $recordedTemp > $maxTemp;

        return self::create([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'type' => self::TYPE_TEMPERATURE_ALERT,
            'severity' => $isOutOfRange ? self::SEVERITY_CRITICAL : self::SEVERITY_WARNING,
            'title' => "Temperature Alert: {$item->name}",
            'message' => $isOutOfRange
                ? "Temperature out of range. Recorded: {$recordedTemp}°C, Range: {$minTemp}°C - {$maxTemp}°C"
                : "Temperature approaching limits. Recorded: {$recordedTemp}°C, Range: {$minTemp}°C - {$maxTemp}°C",
            'recorded_temperature' => $recordedTemp,
            'min_temperature' => $minTemp,
            'max_temperature' => $maxTemp,
            'details' => [
                'location' => $location,
                'is_out_of_range' => $isOutOfRange,
            ],
        ]);
    }

    /**
     * Create a discrepancy alert.
     */
    public static function createDiscrepancyAlert(
        InventoryItem $item,
        float $expectedQuantity,
        float $actualQuantity,
        string $reason
    ): self {
        $difference = abs($expectedQuantity - $actualQuantity);
        $percentageDiff = ($difference / $expectedQuantity) * 100;

        $severity = $percentageDiff > 20 ? self::SEVERITY_CRITICAL :
                   ($percentageDiff > 10 ? self::SEVERITY_WARNING : self::SEVERITY_INFO);

        return self::create([
            'inventory_item_id' => $item->id,
            'branch_id' => $item->branch_id,
            'type' => self::TYPE_DISCREPANCY,
            'severity' => $severity,
            'title' => "Inventory Discrepancy: {$item->name}",
            'message' => "Count discrepancy detected. Expected: {$expectedQuantity}, Actual: {$actualQuantity}, Difference: {$difference} ({$percentageDiff}%)",
            'threshold_value' => $expectedQuantity,
            'current_value' => $actualQuantity,
            'difference' => $actualQuantity - $expectedQuantity,
            'details' => [
                'reason' => $reason,
                'percentage_diff' => $percentageDiff,
            ],
        ]);
    }

    /**
     * Acknowledge the alert.
     */
    public function acknowledge(User $user): self
    {
        $this->status = self::STATUS_ACKNOWLEDGED;
        $this->acknowledged_at = now();
        $this->acknowledged_by = $user->id;
        $this->save();

        return $this;
    }

    /**
     * Resolve the alert.
     */
    public function resolve(User $user, string $action, string $notes = null): self
    {
        $this->status = self::STATUS_RESOLVED;
        $this->resolved_at = now();
        $this->resolved_by = $user->id;
        $this->resolution_action = $action;
        $this->resolution_notes = $notes;
        $this->save();

        return $this;
    }

    /**
     * Dismiss the alert (false alarm).
     */
    public function dismiss(User $user, string $reason): self
    {
        $this->status = self::STATUS_DISMISSED;
        $this->dismissed_at = now();
        $this->dismissed_by = $user->id;
        $this->dismissal_reason = $reason;
        $this->save();

        return $this;
    }

    /**
     * Assign alert to a user.
     */
    public function assignTo(User $user): self
    {
        $this->assigned_to = $user->id;
        $this->assigned_at = now();
        $this->save();

        return $this;
    }

    /**
     * Escalate the alert.
     */
    public function escalate(User $to, string $reason = null): self
    {
        $this->is_escalated = true;
        $this->escalated_at = now();
        $this->escalated_to = $to->id;

        // Add to comment history
        $comment = "Escalated to {$to->name}" . ($reason ? ": {$reason}" : "");
        $this->addComment($comment);

        $this->save();

        return $this;
    }

    /**
     * Add a comment to the alert.
     */
    public function addComment(string $comment, ?User $user = null): self
    {
        $history = $this->comment_history ?? [];
        $history[] = [
            'comment' => $comment,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'created_at' => now(),
        ];

        $this->comment_history = $history;
        $this->save();

        return $this;
    }

    /**
     * Record that a notification was sent.
     */
    public function recordNotification(string $channel, array $recipients): self
    {
        $notifications = $this->notifications_sent ?? [];
        $notifications[] = [
            'channel' => $channel,
            'recipients' => $recipients,
            'sent_at' => now(),
        ];

        $this->notifications_sent = $notifications;
        $this->last_notified_at = now();
        $this->notification_count++;
        $this->save();

        return $this;
    }

    /**
     * Check if alert should be escalated based on SLA.
     */
    public function checkSLA(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE || $this->is_escalated) {
            return false;
        }

        $slaHours = match($this->severity) {
            self::SEVERITY_EMERGENCY => 1,
            self::SEVERITY_CRITICAL => 4,
            self::SEVERITY_WARNING => 24,
            default => 48,
        };

        if ($this->age_in_hours > $slaHours) {
            // Find manager to escalate to
            $manager = User::role('Inventory Manager')->first();

            if ($manager) {
                $this->escalate($manager, "SLA breach: Alert active for {$this->age_in_hours} hours");
                return true;
            }
        }

        return false;
    }

    /**
     * Get alert summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type_info,
            'severity' => $this->severity_info,
            'status' => $this->status_info,
            'title' => $this->title,
            'message' => $this->message,
            'item' => $this->inventoryItem?->name,
            'item_sku' => $this->inventoryItem?->sku,
            'branch' => $this->branch?->name,
            'threshold' => $this->threshold_display,
            'created' => $this->created_at->format('Y-m-d H:i'),
            'time_ago' => $this->time_ago,
            'is_overdue' => $this->is_overdue,
            'assigned_to' => $this->assignedTo?->name,
            'comment_count' => count($this->comment_history ?? []),
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

        static::creating(function ($alert) {
            // Set branch from inventory item if not set
            if (empty($alert->branch_id) && $alert->inventory_item_id) {
                $item = InventoryItem::find($alert->inventory_item_id);
                $alert->branch_id = $item?->branch_id;
            }

            // Calculate difference if threshold and current are set
            if ($alert->threshold_value !== null && $alert->current_value !== null) {
                $alert->difference = $alert->threshold_value - $alert->current_value;
            }
        });

        static::created(function ($alert) {
            // Check if this alert needs immediate escalation
            if ($alert->severity === self::SEVERITY_EMERGENCY) {
                $alert->checkSLA();
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
