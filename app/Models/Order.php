<?php

namespace App\Models;

use App\Services\OrderNumberService;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'uuid',
        'order_number',
        'invoice_number',
        'branch_id',
        'sequence',
        'year',
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
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'tags' => 'array',

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
        ];
    }

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
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by', 'id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(CustomerFeedback::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function updateTotals(): self
    {
        $this->subtotal = $this->items()->sum('subtotal');

        $this->total_amount =
            $this->subtotal
            + $this->delivery_fee
            + $this->pickup_fee
            + $this->extra_charges
            + $this->tax_amount
            - $this->discount_amount;

        $this->balance_due = $this->total_amount - $this->paid_amount;

        $this->save();

        return $this;
    }

    public function updatePaymentStatus(): self
    {
        $totalPaid = $this->payments()->sum('amount');

        $this->paid_amount = $totalPaid;
        $this->balance_due = $this->total_amount - $totalPaid;

        $this->payment_status = match (true) {
            $this->balance_due <= 0 => 'paid',
            $totalPaid > 0 => 'partially_paid',
            default => 'pending',
        };

        $this->save();

        return $this;
    }

    public function updateStatus(string $newStatus, ?string $notes = null): self
    {
        if ($this->status === $newStatus) {
            return $this;
        }

        $oldStatus = $this->status;

        $this->update([
            'status' => $newStatus,
            'status_updated_at' => now(),
            'status_updated_by' => auth()->id(),
            'completed_at' => $newStatus === 'completed' ? now() : $this->completed_at,
            'actual_delivery_date' => $newStatus === 'delivered' ? now() : $this->actual_delivery_date,
        ]);

        $this->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($order) {

            if (!$order->branch_id) {
                throw new \Exception('Branch is required to create an order.');
            }

            $service = app(OrderNumberService::class);

            $numberData = $service->generateForBranch($order->branch_id);

            $order->order_number = $numberData['order_number'];
            $order->sequence = $numberData['sequence'];
            $order->year = $numberData['year'];

            if (empty($order->order_date)) {
                $order->order_date = now();
            }

            if (auth()->check() && empty($order->created_by)) {
                $order->created_by = auth()->id();
            }
        });

        static::updating(function ($order) {
            if (auth()->check()) {
                $order->updated_by = auth()->id();
            }
        });

        static::deleting(function ($order) {
            if ($order->payments()->exists()) {
                throw new \Exception('Cannot delete order with existing payments.');
            }
        });
    }
}
