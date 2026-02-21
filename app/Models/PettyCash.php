<?php
// app/Models/PettyCash.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PettyCash extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'petty_cashes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fund_number',
        'name',
        'code',
        'branch_id',
        'custodian_id',
        'opening_balance',
        'current_balance',
        'minimum_balance',
        'maximum_balance',
        'status',
        'established_date',
        'last_replenished_at',
        'last_counted_at',
        'closed_at',
        'auto_replenish',
        'replenishment_threshold',
        'replenishment_amount',
        'replenishment_method',
        'max_transaction_amount',
        'daily_withdrawal_limit',
        'max_transactions_per_day',
        'requires_approval',
        'approval_threshold',
        'approver_id',
        'account_code',
        'gl_account',
        'location',
        'description',
        'purpose',
        'last_audited_at',
        'last_audited_by',
        'notes',
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
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'minimum_balance' => 'decimal:2',
            'maximum_balance' => 'decimal:2',
            'replenishment_threshold' => 'decimal:2',
            'replenishment_amount' => 'decimal:2',
            'max_transaction_amount' => 'decimal:2',
            'daily_withdrawal_limit' => 'decimal:2',
            'approval_threshold' => 'decimal:2',
            'max_transactions_per_day' => 'integer',
            'established_date' => 'date',
            'last_replenished_at' => 'date',
            'last_counted_at' => 'date',
            'closed_at' => 'date',
            'last_audited_at' => 'datetime',
            'auto_replenish' => 'boolean',
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
        'status' => 'active',
        'minimum_balance' => 0,
        'auto_replenish' => false,
        'requires_approval' => false,
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_CLOSED = 'closed';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_REPLENISHING = 'replenishing';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this petty cash belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the custodian of this petty cash.
     */
    public function custodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'custodian_id');
    }

    /**
     * Get the approver for this petty cash.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Get the user who last audited this petty cash.
     */
    public function lastAuditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_audited_by');
    }

    /**
     * Get all transactions for this petty cash.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PettyCashTransaction::class, 'petty_cash_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active petty cash funds.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include funds below minimum balance.
     */
    public function scopeBelowMinimum($query)
    {
        return $query->whereRaw('current_balance <= minimum_balance')
            ->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by custodian.
     */
    public function scopeForCustodian($query, $userId)
    {
        return $query->where('custodian_id', $userId);
    }

    /**
     * Scope a query to search petty cash funds.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('fund_number', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('custodian', function ($custodianQuery) use ($search) {
                    $custodianQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Generate a unique fund number.
     */
    public static function generateFundNumber(): string
    {
        $year = date('Y');
        $lastFund = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastFund) {
            $lastNumber = intval(substr($lastFund->fund_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PC-{$year}-{$newNumber}";
    }

    /**
     * Generate a unique code.
     */
    public static function generateCode(string $name): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $base = $prefix;

        $count = 1;
        while (self::where('code', $base)->exists()) {
            $base = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
            $count++;
        }

        return $base;
    }

    /**
     * Get status with color and icon.
     */
    public function getStatusInfoAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => [
                'label' => 'Active',
                'color' => 'green',
                'icon' => 'fa-check-circle',
                'badge' => 'bg-green-100 text-green-800',
            ],
            self::STATUS_INACTIVE => [
                'label' => 'Inactive',
                'color' => 'gray',
                'icon' => 'fa-pause-circle',
                'badge' => 'bg-gray-100 text-gray-800',
            ],
            self::STATUS_CLOSED => [
                'label' => 'Closed',
                'color' => 'red',
                'icon' => 'fa-times-circle',
                'badge' => 'bg-red-100 text-red-800',
            ],
            self::STATUS_UNDER_REVIEW => [
                'label' => 'Under Review',
                'color' => 'yellow',
                'icon' => 'fa-search',
                'badge' => 'bg-yellow-100 text-yellow-800',
            ],
            self::STATUS_REPLENISHING => [
                'label' => 'Replenishing',
                'color' => 'blue',
                'icon' => 'fa-sync-alt',
                'badge' => 'bg-blue-100 text-blue-800',
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
     * Get formatted current balance.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'KES ' . number_format($this->current_balance, 2);
    }

    /**
     * Get formatted opening balance.
     */
    public function getFormattedOpeningBalanceAttribute(): string
    {
        return 'KES ' . number_format($this->opening_balance, 2);
    }

    /**
     * Check if fund is below minimum.
     */
    public function getIsBelowMinimumAttribute(): bool
    {
        return $this->current_balance <= $this->minimum_balance;
    }

    /**
     * Get total disbursements today.
     */
    public function getTodayDisbursementsAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'disbursement')
            ->whereDate('transaction_date', today())
            ->sum('amount');
    }

    /**
     * Get today's transaction count.
     */
    public function getTodayTransactionCountAttribute(): int
    {
        return $this->transactions()
            ->whereDate('transaction_date', today())
            ->count();
    }

    /**
     * Check if daily limit is exceeded.
     */
    public function getIsDailyLimitExceededAttribute(): bool
    {
        if (!$this->daily_withdrawal_limit) {
            return false;
        }

        return $this->today_disbursements > $this->daily_withdrawal_limit;
    }

    /**
     * Check if transaction count limit is exceeded.
     */
    public function getIsTransactionLimitExceededAttribute(): bool
    {
        if (!$this->max_transactions_per_day) {
            return false;
        }

        return $this->today_transaction_count >= $this->max_transactions_per_day;
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Initialize a new petty cash fund.
     */
    public static function initialize(array $data): self
    {
        $data['fund_number'] = self::generateFundNumber();
        $data['code'] = self::generateCode($data['name']);
        $data['current_balance'] = $data['opening_balance'];
        $data['established_date'] = $data['established_date'] ?? now();

        $pettyCash = self::create($data);

        // Create initial transaction record
        $pettyCash->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'replenishment',
            'direction' => 'in',
            'amount' => $data['opening_balance'],
            'balance_before' => 0,
            'balance_after' => $data['opening_balance'],
            'transaction_date' => $data['established_date'],
            'description' => 'Initial fund establishment',
            'created_by' => auth()->id(),
        ]);

        return $pettyCash;
    }

    /**
     * Create a disbursement (expense).
     */
    public function disburse(
        float $amount,
        string $description,
        ?string $payeeName = null,
        ?string $receiptNumber = null,
        ?array $metadata = []
    ): PettyCashTransaction {
        // Validate amount
        if ($amount <= 0) {
            throw new \Exception('Amount must be greater than zero.');
        }

        if ($amount > $this->current_balance) {
            throw new \Exception('Insufficient funds in petty cash.');
        }

        if ($this->max_transaction_amount && $amount > $this->max_transaction_amount) {
            throw new \Exception("Amount exceeds maximum transaction amount of {$this->max_transaction_amount}.");
        }

        if ($this->daily_withdrawal_limit && ($this->today_disbursements + $amount) > $this->daily_withdrawal_limit) {
            throw new \Exception('Daily withdrawal limit would be exceeded.');
        }

        if ($this->max_transactions_per_day && $this->today_transaction_count >= $this->max_transactions_per_day) {
            throw new \Exception('Daily transaction limit reached.');
        }

        $balanceBefore = $this->current_balance;
        $balanceAfter = $balanceBefore - $amount;

        $requiresApproval = $this->requires_approval && $this->approval_threshold && $amount > $this->approval_threshold;

        $transaction = $this->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'disbursement',
            'direction' => 'out',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'transaction_date' => now(),
            'description' => $description,
            'payee_name' => $payeeName,
            'receipt_number' => $receiptNumber,
            'requires_approval' => $requiresApproval,
            'approval_status' => $requiresApproval ? 'pending' : 'approved',
            'metadata' => $metadata,
            'created_by' => auth()->id(),
        ]);

        // Update balance only if approved (or no approval needed)
        if (!$requiresApproval) {
            $this->current_balance = $balanceAfter;
            $this->save();
        }

        return $transaction;
    }

    /**
     * Replenish the petty cash fund.
     */
    public function replenish(float $amount, string $description, ?array $metadata = []): PettyCashTransaction
    {
        $balanceBefore = $this->current_balance;
        $balanceAfter = $balanceBefore + $amount;

        $transaction = $this->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'replenishment',
            'direction' => 'in',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'transaction_date' => now(),
            'description' => $description,
            'metadata' => $metadata,
            'created_by' => auth()->id(),
        ]);

        $this->current_balance = $balanceAfter;
        $this->last_replenished_at = now();
        $this->status = self::STATUS_ACTIVE;
        $this->save();

        return $transaction;
    }

    /**
     * Adjust balance (for corrections).
     */
    public function adjustBalance(float $newBalance, string $reason, ?array $metadata = []): PettyCashTransaction
    {
        $difference = $newBalance - $this->current_balance;

        if ($difference == 0) {
            throw new \Exception('New balance is same as current balance.');
        }

        $transaction = $this->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'adjustment',
            'direction' => $difference > 0 ? 'in' : 'out',
            'amount' => abs($difference),
            'balance_before' => $this->current_balance,
            'balance_after' => $newBalance,
            'transaction_date' => now(),
            'description' => 'Balance adjustment: ' . $reason,
            'metadata' => $metadata,
            'created_by' => auth()->id(),
        ]);

        $this->current_balance = $newBalance;
        $this->save();

        return $transaction;
    }

    /**
     * Transfer money to another petty cash fund.
     */
    public function transferTo(self $destinationFund, float $amount, string $reason): array
    {
        if ($amount > $this->current_balance) {
            throw new \Exception('Insufficient funds for transfer.');
        }

        // Create outbound transaction from source
        $outTransaction = $this->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'transfer',
            'direction' => 'out',
            'amount' => $amount,
            'balance_before' => $this->current_balance,
            'balance_after' => $this->current_balance - $amount,
            'transaction_date' => now(),
            'description' => "Transfer to {$destinationFund->name}: {$reason}",
            'metadata' => ['destination_fund_id' => $destinationFund->id],
            'created_by' => auth()->id(),
        ]);

        $this->current_balance -= $amount;
        $this->save();

        // Create inbound transaction at destination
        $inTransaction = $destinationFund->transactions()->create([
            'transaction_number' => PettyCashTransaction::generateTransactionNumber(),
            'type' => 'transfer',
            'direction' => 'in',
            'amount' => $amount,
            'balance_before' => $destinationFund->current_balance,
            'balance_after' => $destinationFund->current_balance + $amount,
            'transaction_date' => now(),
            'description' => "Transfer from {$this->name}: {$reason}",
            'metadata' => ['source_fund_id' => $this->id],
            'created_by' => auth()->id(),
        ]);

        $destinationFund->current_balance += $amount;
        $destinationFund->save();

        return [
            'source_transaction' => $outTransaction,
            'destination_transaction' => $inTransaction,
        ];
    }

    /**
     * Close the petty cash fund.
     */
    public function close(string $reason): self
    {
        if ($this->current_balance != 0) {
            throw new \Exception('Cannot close fund with non-zero balance. Balance must be zero.');
        }

        $this->status = self::STATUS_CLOSED;
        $this->closed_at = now();
        $this->notes = ($this->notes ? $this->notes . "\n\n" : '') . "Closed: {$reason}";
        $this->save();

        return $this;
    }

    /**
     * Audit the petty cash fund.
     */
    public function audit(User $auditor, float $countedBalance, string $notes = null): array
    {
        $discrepancy = $countedBalance - $this->current_balance;

        $audit = [
            'audited_at' => now(),
            'audited_by' => $auditor->id,
            'expected_balance' => $this->current_balance,
            'counted_balance' => $countedBalance,
            'discrepancy' => $discrepancy,
            'notes' => $notes,
        ];

        $this->last_audited_at = now();
        $this->last_audited_by = $auditor->id;

        if ($discrepancy != 0) {
            $this->status = self::STATUS_UNDER_REVIEW;
        }

        $this->metadata = array_merge($this->metadata ?? [], ['last_audit' => $audit]);
        $this->save();

        return $audit;
    }

    /**
     * Check if auto-replenishment is needed.
     */
    public function checkAutoReplenishment(): ?float
    {
        if (!$this->auto_replenish || !$this->replenishment_threshold) {
            return null;
        }

        if ($this->current_balance <= $this->replenishment_threshold) {
            $amount = $this->replenishment_amount ?? ($this->maximum_balance - $this->current_balance);

            if ($amount > 0) {
                $this->status = self::STATUS_REPLENISHING;
                $this->save();

                return $amount;
            }
        }

        return null;
    }

    /**
     * Get summary report.
     */
    public function getSummary(): array
    {
        $todayDisbursements = $this->transactions()
            ->where('type', 'disbursement')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        $monthDisbursements = $this->transactions()
            ->where('type', 'disbursement')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        return [
            'id' => $this->id,
            'fund_number' => $this->fund_number,
            'name' => $this->name,
            'code' => $this->code,
            'custodian' => $this->custodian?->name,
            'branch' => $this->branch?->name,
            'current_balance' => $this->formatted_balance,
            'opening_balance' => $this->formatted_opening_balance,
            'minimum_balance' => 'KES ' . number_format($this->minimum_balance, 2),
            'status' => $this->status_info,
            'is_below_minimum' => $this->is_below_minimum,
            'today_disbursements' => 'KES ' . number_format($todayDisbursements, 2),
            'month_disbursements' => 'KES ' . number_format($monthDisbursements, 2),
            'transaction_count' => $this->transactions()->count(),
            'last_replenished' => $this->last_replenished_at?->format('Y-m-d'),
            'last_audited' => $this->last_audited_at?->format('Y-m-d'),
            'established_date' => $this->established_date->format('Y-m-d'),
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

        static::creating(function ($pettyCash) {
            // Generate fund number if not set
            if (empty($pettyCash->fund_number)) {
                $pettyCash->fund_number = self::generateFundNumber();
            }

            // Generate code if not set
            if (empty($pettyCash->code)) {
                $pettyCash->code = self::generateCode($pettyCash->name);
            }

            // Set current balance to opening balance
            $pettyCash->current_balance = $pettyCash->opening_balance;

            // Set established date if not set
            if (empty($pettyCash->established_date)) {
                $pettyCash->established_date = now();
            }
        });

        static::updating(function ($pettyCash) {
            // Check if fund is being closed
            if ($pettyCash->isDirty('status') && $pettyCash->status === self::STATUS_CLOSED) {
                if ($pettyCash->current_balance != 0) {
                    throw new \Exception('Cannot close fund with non-zero balance.');
                }
                $pettyCash->closed_at = now();
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
};
