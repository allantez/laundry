<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_number',
        'receipt_number',
        'order_id',
        'customer_id',
        'branch_id',
        'created_by',
        'updated_by',
        'amount',
        'tip_amount',
        'change_amount',
        'total_amount',
        'payment_method',
        'payment_submethod',
        'transaction_id',
        'reference_number',
        'authorization_code',
        'status',
        'payment_date',
        'completed_at',
        'refunded_amount',
        'refunded_at',
        'refunded_by',
        'refund_reason',
        'parent_payment_id',
        'split_sequence',
        'credit_account_id',
        'cheque_number',
        'cheque_date',
        'cheque_bank',
        'cheque_cleared_at',
        'mpesa_number',
        'mpesa_first_name',
        'mpesa_middle_name',
        'mpesa_last_name',
        'mpesa_transaction_time',
        'card_last_four',
        'card_type',
        'card_holder_name',
        'notes',
        'staff_notes',
        'metadata',
        'is_reconciled',
        'reconciled_at',
        'reconciled_by',
        'receipt_issued',
        'receipt_issued_at',
        'receipt_path',
        'is_flagged',
        'flag_reason',
        'flagged_by',
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
            'amount' => 'decimal:2',
            'tip_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'refunded_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'completed_at' => 'datetime',
            'refunded_at' => 'datetime',
            'cheque_date' => 'date',
            'cheque_cleared_at' => 'datetime',
            'mpesa_transaction_time' => 'datetime',
            'reconciled_at' => 'datetime',
            'receipt_issued_at' => 'datetime',
            'is_reconciled' => 'boolean',
            'receipt_issued' => 'boolean',
            'is_flagged' => 'boolean',
            'split_sequence' => 'integer',
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
        'payment_method' => 'cash',
        'tip_amount' => 0,
        'change_amount' => 0,
        'refunded_amount' => 0,
        'is_reconciled' => false,
        'receipt_issued' => false,
        'is_flagged' => false,
    ];

    // =========================================================================
    // STATUS CONSTANTS
    // =========================================================================

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_CANCELLED = 'cancelled';

    const METHOD_CASH = 'cash';
    const METHOD_MPESA = 'mpesa';
    const METHOD_CARD = 'card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CHEQUE = 'cheque';
    const METHOD_CREDIT_ACCOUNT = 'credit_account';
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_VOUCHER = 'voucher';
    const METHOD_GIFT_CARD = 'gift_card';
    const METHOD_OTHER = 'other';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the order that this payment belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer that made this payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the branch that received this payment.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created this payment.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this payment.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who refunded this payment.
     */
    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    /**
     * Get the parent payment (for split payments).
     */
    public function parentPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'parent_payment_id');
    }

    /**
     * Get child payments (for split payments).
     */
    public function childPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'parent_payment_id');
    }

    /**
     * Get the credit account used for this payment.
     */
    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(CreditAccount::class);
    }

    /**
     * Get the user who reconciled this payment.
     */
    public function reconciledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    /**
     * Get the user who flagged this payment.
     */
    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include payments with a specific status.
     */
    public function scopeWhereStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope a query to only include refunded payments.
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }

    /**
     * Scope a query to filter by payment method.
     */
    public function scopeWithMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to only include cash payments.
     */
    public function scopeCash($query)
    {
        return $query->where('payment_method', self::METHOD_CASH);
    }

    /**
     * Scope a query to only include M-Pesa payments.
     */
    public function scopeMpesa($query)
    {
        return $query->where('payment_method', self::METHOD_MPESA);
    }

    /**
     * Scope a query to only include card payments.
     */
    public function scopeCard($query)
    {
        return $query->where('payment_method', self::METHOD_CARD);
    }

    /**
     * Scope a query to filter by date.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('payment_date', $date);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
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
     * Scope a query to only include reconciled payments.
     */
    public function scopeReconciled($query)
    {
        return $query->where('is_reconciled', true);
    }

    /**
     * Scope a query to only include unreconciled payments.
     */
    public function scopeUnreconciled($query)
    {
        return $query->where('is_reconciled', false);
    }

    /**
     * Scope a query to only include flagged payments.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope a query to search payments.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('payment_number', 'like', "%{$search}%")
              ->orWhere('receipt_number', 'like', "%{$search}%")
              ->orWhere('transaction_id', 'like', "%{$search}%")
              ->orWhere('reference_number', 'like', "%{$search}%")
              ->orWhere('mpesa_number', 'like', "%{$search}%")
              ->orWhere('cheque_number', 'like', "%{$search}%")
              ->orWhereHas('order', function ($orderQuery) use ($search) {
                  $orderQuery->where('order_number', 'like', "%{$search}%");
              })
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
     * Get the payment status with color and icon.
     */
    public function getStatusInfoAttribute(): array
    {
        return match($this->status) {
            self::STATUS_COMPLETED => [
                'label' => 'Completed',
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
            self::STATUS_FAILED => [
                'label' => 'Failed',
                'color' => 'red',
                'icon' => 'fa-times-circle',
                'badge' => 'bg-red-100 text-red-800',
            ],
            self::STATUS_REFUNDED => [
                'label' => 'Refunded',
                'color' => 'purple',
                'icon' => 'fa-undo',
                'badge' => 'bg-purple-100 text-purple-800',
            ],
            self::STATUS_PARTIALLY_REFUNDED => [
                'label' => 'Partially Refunded',
                'color' => 'orange',
                'icon' => 'fa-undo-alt',
                'badge' => 'bg-orange-100 text-orange-800',
            ],
            self::STATUS_CANCELLED => [
                'label' => 'Cancelled',
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
     * Get payment method with icon.
     */
    public function getPaymentMethodInfoAttribute(): array
    {
        return match($this->payment_method) {
            self::METHOD_CASH => [
                'label' => 'Cash',
                'icon' => 'fa-money-bill',
                'color' => 'green',
            ],
            self::METHOD_MPESA => [
                'label' => 'M-Pesa',
                'icon' => 'fa-mobile-alt',
                'color' => 'green',
            ],
            self::METHOD_CARD => [
                'label' => 'Card',
                'icon' => 'fa-credit-card',
                'color' => 'blue',
            ],
            self::METHOD_BANK_TRANSFER => [
                'label' => 'Bank Transfer',
                'icon' => 'fa-university',
                'color' => 'purple',
            ],
            self::METHOD_CHEQUE => [
                'label' => 'Cheque',
                'icon' => 'fa-file-invoice',
                'color' => 'orange',
            ],
            self::METHOD_CREDIT_ACCOUNT => [
                'label' => 'Credit Account',
                'icon' => 'fa-credit-card',
                'color' => 'yellow',
            ],
            self::METHOD_MOBILE_MONEY => [
                'label' => 'Mobile Money',
                'icon' => 'fa-mobile-alt',
                'color' => 'blue',
            ],
            self::METHOD_VOUCHER => [
                'label' => 'Voucher',
                'icon' => 'fa-ticket-alt',
                'color' => 'teal',
            ],
            self::METHOD_GIFT_CARD => [
                'label' => 'Gift Card',
                'icon' => 'fa-gift',
                'color' => 'pink',
            ],
            default => [
                'label' => ucfirst($this->payment_method),
                'icon' => 'fa-credit-card',
                'color' => 'gray',
            ],
        };
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'KES ' . number_format($this->amount, 2);
    }

    /**
     * Get formatted total amount with currency.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'KES ' . number_format($this->total_amount, 2);
    }

    /**
     * Get formatted tip amount with currency.
     */
    public function getFormattedTipAttribute(): string
    {
        return 'KES ' . number_format($this->tip_amount, 2);
    }

    /**
     * Get formatted change amount with currency.
     */
    public function getFormattedChangeAttribute(): string
    {
        return 'KES ' . number_format($this->change_amount, 2);
    }

    /**
     * Get formatted refunded amount with currency.
     */
    public function getFormattedRefundedAttribute(): string
    {
        return 'KES ' . number_format($this->refunded_amount, 2);
    }

    /**
     * Get the M-Pesa customer name.
     */
    public function getMpesaFullNameAttribute(): ?string
    {
        if (!$this->mpesa_first_name && !$this->mpesa_middle_name && !$this->mpesa_last_name) {
            return null;
        }

        return trim(implode(' ', array_filter([
            $this->mpesa_first_name,
            $this->mpesa_middle_name,
            $this->mpesa_last_name,
        ])));
    }

    /**
     * Get masked card number.
     */
    public function getMaskedCardAttribute(): ?string
    {
        if (!$this->card_last_four) {
            return null;
        }

        return '•••• •••• •••• ' . $this->card_last_four;
    }

    /**
     * Check if payment is fully refunded.
     */
    public function getIsFullyRefundedAttribute(): bool
    {
        return $this->refunded_amount >= $this->total_amount;
    }

    /**
     * Get refundable amount.
     */
    public function getRefundableAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->refunded_amount);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Generate a unique payment number.
     */
    public static function generatePaymentNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastPayment = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->payment_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PAY-{$year}{$month}-{$newNumber}";
    }

    /**
     * Generate a unique receipt number.
     */
    public function generateReceiptNumber(): string
    {
        $this->receipt_number = "RCT-" . date('Ymd') . "-" . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->save();

        return $this->receipt_number;
    }

    /**
     * Mark payment as completed.
     */
    public function markAsCompleted(?array $metadata = null): self
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();

        if ($metadata) {
            $this->metadata = array_merge($this->metadata ?? [], $metadata);
        }

        $this->save();

        // Update order payment status
        $this->order->updatePaymentStatus();

        // Generate receipt number if not exists
        if (!$this->receipt_number) {
            $this->generateReceiptNumber();
        }

        return $this;
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason = null): self
    {
        $this->status = self::STATUS_FAILED;
        $this->notes = $reason ? ($this->notes . "\n\nFailed: " . $reason) : $this->notes;
        $this->save();

        return $this;
    }

    /**
     * Process a refund.
     */
    public function refund(float $amount, User $refundedBy, string $reason = null): self
    {
        if ($amount > $this->refundable_amount) {
            throw new \Exception('Refund amount exceeds refundable amount.');
        }

        $this->refunded_amount += $amount;
        $this->refunded_at = now();
        $this->refunded_by = $refundedBy->id;
        $this->refund_reason = $reason;

        // Update status based on refund amount
        if ($this->refunded_amount >= $this->total_amount) {
            $this->status = self::STATUS_REFUNDED;
        } else {
            $this->status = self::STATUS_PARTIALLY_REFUNDED;
        }

        $this->save();

        // Update order payment status
        $this->order->updatePaymentStatus();

        // Create refund record (optional - could be in separate refunds table)
        $this->metadata = array_merge($this->metadata ?? [], [
            'refunds' => array_merge($this->metadata['refunds'] ?? [], [
                [
                    'amount' => $amount,
                    'refunded_by' => $refundedBy->id,
                    'refunded_at' => now(),
                    'reason' => $reason,
                ]
            ])
        ]);

        $this->save();

        return $this;
    }

    /**
     * Mark payment as reconciled.
     */
    public function markAsReconciled(User $reconciledBy): self
    {
        $this->is_reconciled = true;
        $this->reconciled_at = now();
        $this->reconciled_by = $reconciledBy->id;
        $this->save();

        return $this;
    }

    /**
     * Issue receipt.
     */
    public function issueReceipt(string $receiptPath = null): self
    {
        $this->receipt_issued = true;
        $this->receipt_issued_at = now();

        if ($receiptPath) {
            $this->receipt_path = $receiptPath;
        }

        $this->save();

        return $this;
    }

    /**
     * Flag payment for attention.
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
     * Unflag payment.
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
     * Process cash payment.
     */
    public static function processCashPayment(Order $order, float $amount, float $tendered, User $createdBy): self
    {
        $change = max(0, $tendered - $amount);

        $payment = new self([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'branch_id' => $order->branch_id,
            'created_by' => $createdBy->id,
            'amount' => $amount,
            'change_amount' => $change,
            'total_amount' => $amount,
            'payment_method' => self::METHOD_CASH,
            'payment_date' => now(),
        ]);

        $payment->payment_number = self::generatePaymentNumber();
        $payment->save();

        return $payment->markAsCompleted(['tendered' => $tendered, 'change' => $change]);
    }

    /**
     * Process M-Pesa payment.
     */
    public static function processMpesaPayment(Order $order, float $amount, string $mpesaNumber, string $transactionId, User $createdBy): self
    {
        $payment = new self([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'branch_id' => $order->branch_id,
            'created_by' => $createdBy->id,
            'amount' => $amount,
            'total_amount' => $amount,
            'payment_method' => self::METHOD_MPESA,
            'mpesa_number' => $mpesaNumber,
            'transaction_id' => $transactionId,
            'reference_number' => $transactionId,
            'payment_date' => now(),
        ]);

        $payment->payment_number = self::generatePaymentNumber();
        $payment->save();

        return $payment;
    }

    /**
     * Process card payment.
     */
    public static function processCardPayment(Order $order, float $amount, string $cardLastFour, string $cardType, string $authCode, User $createdBy): self
    {
        $payment = new self([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'branch_id' => $order->branch_id,
            'created_by' => $createdBy->id,
            'amount' => $amount,
            'total_amount' => $amount,
            'payment_method' => self::METHOD_CARD,
            'card_last_four' => $cardLastFour,
            'card_type' => $cardType,
            'authorization_code' => $authCode,
            'transaction_id' => $authCode,
            'payment_date' => now(),
        ]);

        $payment->payment_number = self::generatePaymentNumber();
        $payment->save();

        return $payment->markAsCompleted();
    }

    /**
     * Get payment summary.
     */
    public function getSummary(): array
    {
        return [
            'payment_number' => $this->payment_number,
            'receipt_number' => $this->receipt_number,
            'order_number' => $this->order->order_number,
            'customer' => $this->customer ? $this->customer->full_name : 'Walk-in',
            'amount' => $this->formatted_amount,
            'total' => $this->formatted_total,
            'method' => $this->payment_method_info,
            'status' => $this->status_info,
            'payment_date' => $this->payment_date->format('Y-m-d H:i'),
            'is_reconciled' => $this->is_reconciled,
            'reference' => $this->reference_number ?? $this->transaction_id,
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

        static::creating(function ($payment) {
            // Generate payment number
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }

            // Set customer_id from order if not set
            if (empty($payment->customer_id) && $payment->order) {
                $payment->customer_id = $payment->order->customer_id;
            }

            // Set total amount if not set
            if (empty($payment->total_amount)) {
                $payment->total_amount = $payment->amount + ($payment->tip_amount ?? 0);
            }
        });

        static::created(function ($payment) {
            // Update order payment status
            $payment->order->updatePaymentStatus();

            // Generate receipt number
            if ($payment->status === self::STATUS_COMPLETED && !$payment->receipt_number) {
                $payment->generateReceiptNumber();
            }
        });

        static::updating(function ($payment) {
            // Track status changes
            if ($payment->isDirty('status')) {
                $payment->metadata = array_merge($payment->metadata ?? [], [
                    'status_changes' => array_merge($payment->metadata['status_changes'] ?? [], [
                        [
                            'old_status' => $payment->getOriginal('status'),
                            'new_status' => $payment->status,
                            'changed_by' => auth()->id(),
                            'changed_at' => now(),
                        ]
                    ])
                ]);
            }
        });

        static::updated(function ($payment) {
            // Update order payment status if amount changed
            if ($payment->isDirty(['amount', 'refunded_amount', 'status'])) {
                $payment->order->updatePaymentStatus();
            }
        });

        static::deleted(function ($payment) {
            // Update order payment status
            $payment->order->updatePaymentStatus();
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
