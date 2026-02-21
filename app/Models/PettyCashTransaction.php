<?php
// app/Models/PettyCashTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PettyCash;

class PettyCashTransaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'petty_cash_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_number',
        'petty_cash_id',
        'type',
        'direction',
        'amount',
        'balance_before',
        'balance_after',
        'transaction_date',
        'recorded_at',
        'expense_category_id',
        'reference_type',
        'reference_id',
        'receipt_number',
        'payee_name',
        'payee_type',
        'description',
        'notes',
        'requires_approval',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'receipt_path',
        'attachments',
        'created_by',
        'updated_by',
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
            'attachments' => 'json',
            'metadata' => 'json',
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'transaction_date' => 'date',
            'recorded_at' => 'datetime',
            'approved_at' => 'datetime',
            'requires_approval' => 'boolean',
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
        'approval_status' => 'approved',
        'requires_approval' => false,
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    const TYPE_DISBURSEMENT = 'disbursement';
    const TYPE_REPLENISHMENT = 'replenishment';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_REFUND = 'refund';

    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';

    const APPROVAL_STATUS_PENDING = 'pending';
    const APPROVAL_STATUS_APPROVED = 'approved';
    const APPROVAL_STATUS_REJECTED = 'rejected';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the petty cash fund for this transaction.
     */
    public function pettyCash(): BelongsTo
    {
        return $this->belongsTo(PettyCash::class);
    }

    /**
     * Get the expense category for this transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Get the user who approved this transaction.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who created this transaction.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this transaction.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
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
     * Scope a query to only include disbursements.
     */
    public function scopeDisbursements($query)
    {
        return $query->where('type', self::TYPE_DISBURSEMENT);
    }

    /**
     * Scope a query to only include replenishments.
     */
    public function scopeReplenishments($query)
    {
        return $query->where('type', self::TYPE_REPLENISHMENT);
    }

    /**
     * Scope a query to only include inbound transactions.
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', self::DIRECTION_IN);
    }

    /**
     * Scope a query to only include outbound transactions.
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', self::DIRECTION_OUT);
    }

    /**
     * Scope a query to only include pending approval.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', self::APPROVAL_STATUS_PENDING);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Generate a unique transaction number.
     */
    public static function generateTransactionNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastTransaction = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PCT-{$year}{$month}-{$newNumber}";
    }

    /**
     * Get transaction type with icon.
     */
    public function getTypeInfoAttribute(): array
    {
        return match($this->type) {
            self::TYPE_DISBURSEMENT => [
                'label' => 'Disbursement',
                'icon' => 'fa-arrow-up',
                'color' => 'red',
            ],
            self::TYPE_REPLENISHMENT => [
                'label' => 'Replenishment',
                'icon' => 'fa-arrow-down',
                'color' => 'green',
            ],
            self::TYPE_ADJUSTMENT => [
                'label' => 'Adjustment',
                'icon' => 'fa-sliders-h',
                'color' => 'orange',
            ],
            self::TYPE_TRANSFER => [
                'label' => 'Transfer',
                'icon' => 'fa-exchange-alt',
                'color' => 'blue',
            ],
            self::TYPE_REFUND => [
                'label' => 'Refund',
                'icon' => 'fa-undo',
                'color' => 'purple',
            ],
            default => [
                'label' => ucfirst($this->type),
                'icon' => 'fa-circle',
                'color' => 'gray',
            ],
        };
    }

    /**
     * Get formatted amount with direction.
     */
    public function getFormattedAmountAttribute(): string
    {
        $prefix = $this->direction === self::DIRECTION_IN ? '+' : '-';
        return $prefix . ' KES ' . number_format($this->amount, 2);
    }

    /**
     * Get approval status with color.
     */
    public function getApprovalStatusInfoAttribute(): array
    {
        return match($this->approval_status) {
            self::APPROVAL_STATUS_APPROVED => [
                'label' => 'Approved',
                'color' => 'green',
                'icon' => 'fa-check-circle',
            ],
            self::APPROVAL_STATUS_PENDING => [
                'label' => 'Pending',
                'color' => 'yellow',
                'icon' => 'fa-clock',
            ],
            self::APPROVAL_STATUS_REJECTED => [
                'label' => 'Rejected',
                'color' => 'red',
                'icon' => 'fa-times-circle',
            ],
            default => [
                'label' => ucfirst($this->approval_status),
                'color' => 'gray',
                'icon' => 'fa-question',
            ],
        };
    }

    /**
     * Get transaction summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'number' => $this->transaction_number,
            'type' => $this->type_info,
            'amount' => $this->formatted_amount,
            'description' => $this->description,
            'date' => $this->transaction_date->format('Y-m-d'),
            'balance_before' => 'KES ' . number_format($this->balance_before, 2),
            'balance_after' => 'KES ' . number_format($this->balance_after, 2),
            'payee' => $this->payee_name,
            'receipt' => $this->receipt_number,
            'approval' => $this->approval_status_info,
            'created_by' => $this->createdBy?->name,
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

        static::creating(function ($transaction) {
            // Generate transaction number if not set
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = self::generateTransactionNumber();
            }

            // Set recorded_at if not set
            if (empty($transaction->recorded_at)) {
                $transaction->recorded_at = now();
            }

            // Set created_by if not set
            if (empty($transaction->created_by) && auth()->check()) {
                $transaction->created_by = auth()->id();
            }
        });

        static::created(function ($transaction) {
            // If transaction is approved, update the fund balance
            if ($transaction->approval_status === self::APPROVAL_STATUS_APPROVED) {
                $fund = $transaction->pettyCash;
                $fund->current_balance = $transaction->balance_after;
                $fund->save();
            }
        });

        static::updating(function ($transaction) {
            $transaction->updated_by = auth()->id();
        });

        static::updated(function ($transaction) {
            // If approval status changed to approved, update fund balance
            if ($transaction->isDirty('approval_status') &&
                $transaction->approval_status === self::APPROVAL_STATUS_APPROVED) {
                $fund = $transaction->pettyCash;
                $fund->current_balance = $transaction->balance_after;
                $fund->save();
            }
        });
    }
}
