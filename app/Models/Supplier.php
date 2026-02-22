<?php
// app/Models/Supplier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'business_type',
        'tax_number',
        'registration_number',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_branch',
        'bank_swift_code',
        'bank_sort_code',
        'payment_terms',
        'payment_due_days',
        'credit_limit',
        'current_balance',
        'products_supplied',
        'service_areas',
        'minimum_order_value',
        'delivery_fee',
        'lead_time_days',
        'contract_start_date',
        'contract_end_date',
        'is_exclusive',
        'contract_file',
        'rating',
        'total_orders',
        'total_spent',
        'on_time_delivery_rate',
        'quality_rating',
        'is_active',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes',
        'tags',
        'documents',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'products_supplied' => 'json',
            'service_areas' => 'json',
            'tags' => 'json',
            'documents' => 'json',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'is_exclusive' => 'boolean',
            'credit_limit' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'minimum_order_value' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'rating' => 'decimal:2',
            'total_orders' => 'integer',
            'total_spent' => 'decimal:2',
            'on_time_delivery_rate' => 'decimal:2',
            'quality_rating' => 'decimal:2',
            'payment_due_days' => 'integer',
            'lead_time_days' => 'integer',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'approved_at' => 'datetime',
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
        'business_type' => 'individual',
        'payment_terms' => 'bank_transfer',
        'payment_due_days' => 30,
        'is_active' => true,
        'is_approved' => false,
        'is_exclusive' => false,
        'total_orders' => 0,
        'total_spent' => 0,
        'current_balance' => 0,
        'country' => 'Kenya',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this supplier serves.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who approved this supplier.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all inventory items from this supplier.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get all purchases from this supplier.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class); // You'll need to create this model
    }

    /**
     * Get all payments made to this supplier.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class); // You'll need to create this model
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active suppliers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive suppliers.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include approved suppliers.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include pending approval.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope a query to filter by business type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('business_type', $type);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by city.
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope a query to only include exclusive suppliers.
     */
    public function scopeExclusive($query)
    {
        return $query->where('is_exclusive', true);
    }

    /**
     * Scope a query to only include suppliers with credit.
     */
    public function scopeWithCredit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    /**
     * Scope a query to only include suppliers with outstanding balance.
     */
    public function scopeWithBalance($query)
    {
        return $query->where('current_balance', '>', 0);
    }

    /**
     * Scope a query to filter by minimum rating.
     */
    public function scopeMinRating($query, float $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope a query to search suppliers.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('tax_number', 'like', "%{$search}%")
                ->orWhere('registration_number', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by products supplied.
     */
    public function scopeSuppliesProduct($query, string $product)
    {
        return $query->whereJsonContains('products_supplied', $product);
    }

    /**
     * Scope a query to order by rating.
     */
    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    // =========================================================================
    // ACCESSORS & MUTATORS
    // =========================================================================

    /**
     * Get the supplier's full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get supplier's primary contact number.
     */
    public function getPrimaryPhoneAttribute(): string
    {
        return $this->mobile ?? $this->phone ?? 'No phone';
    }

    /**
     * Get status with color.
     */
    public function getStatusAttribute(): array
    {
        if (!$this->is_active) {
            return ['label' => 'Inactive', 'color' => 'red', 'icon' => 'fa-ban'];
        }

        if (!$this->is_approved) {
            return ['label' => 'Pending Approval', 'color' => 'orange', 'icon' => 'fa-clock'];
        }

        if ($this->contract_end_date && $this->contract_end_date->isPast()) {
            return ['label' => 'Contract Expired', 'color' => 'yellow', 'icon' => 'fa-exclamation-triangle'];
        }

        return ['label' => 'Active', 'color' => 'green', 'icon' => 'fa-check-circle'];
    }

    /**
     * Get business type label.
     */
    public function getBusinessTypeLabelAttribute(): string
    {
        return match ($this->business_type) {
            'individual' => 'Individual',
            'company' => 'Company',
            'manufacturer' => 'Manufacturer',
            'distributor' => 'Distributor',
            default => ucfirst($this->business_type),
        };
    }

    /**
     * Get payment terms label.
     */
    public function getPaymentTermsLabelAttribute(): string
    {
        $terms = match ($this->payment_terms) {
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'mpesa' => 'M-Pesa',
            'credit' => 'Credit',
            default => ucfirst($this->payment_terms),
        };

        if ($this->payment_due_days && $this->payment_terms === 'credit') {
            $terms .= " (Net {$this->payment_due_days})";
        }

        return $terms;
    }

    /**
     * Get rating stars HTML.
     */
    public function getRatingStarsAttribute(): string
    {
        if (!$this->rating) {
            return 'Not rated';
        }

        $stars = '';
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                $stars .= '★';
            } elseif ($i == $fullStars + 1 && $halfStar) {
                $stars .= '½';
            } else {
                $stars .= '☆';
            }
        }

        return $stars . " ({$this->rating})";
    }

    /**
     * Check if supplier has active contract.
     */
    public function getHasActiveContractAttribute(): bool
    {
        if (!$this->contract_start_date) {
            return false;
        }

        $now = now();

        if ($this->contract_end_date) {
            return $now >= $this->contract_start_date && $now <= $this->contract_end_date;
        }

        return $now >= $this->contract_start_date;
    }

    /**
     * Get available credit.
     */
    public function getAvailableCreditAttribute(): ?float
    {
        if (!$this->credit_limit) {
            return null;
        }

        return max(0, $this->credit_limit - $this->current_balance);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Approve the supplier.
     */
    public function approve(?User $approvedBy = null): self
    {
        $this->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => $approvedBy?->id,
        ]);

        return $this;
    }

    /**
     * Activate the supplier.
     */
    public function activate(): self
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Deactivate the supplier.
     */
    public function deactivate(?string $reason = null): self
    {
        $this->update([
            'is_active' => false,
            'notes' => $reason ? $this->notes . "\n\nDeactivated: " . $reason : $this->notes,
        ]);

        return $this;
    }

    /**
     * Update rating based on performance.
     */
    public function updateRating(): self
    {
        // Calculate average of quality rating and on-time delivery
        $ratings = [];

        if ($this->quality_rating) {
            $ratings[] = $this->quality_rating;
        }

        if ($this->on_time_delivery_rate) {
            // Convert percentage to 1-5 scale
            $ratings[] = ($this->on_time_delivery_rate / 100) * 5;
        }

        if (empty($ratings)) {
            return $this;
        }

        $this->rating = round(array_sum($ratings) / count($ratings), 2);
        $this->save();

        return $this;
    }

    /**
     * Record a new order from this supplier.
     */
    public function recordOrder(float $amount): self
    {
        $this->increment('total_orders');
        $this->increment('total_spent', $amount);

        if ($this->payment_terms === 'credit') {
            $this->increment('current_balance', $amount);
        }

        return $this;
    }

    /**
     * Record a payment to this supplier.
     */
    public function recordPayment(float $amount): self
    {
        $this->decrement('current_balance', $amount);
        return $this;
    }

    /**
     * Check if supplier can accept new orders.
     */
    public function canAcceptOrder(float $orderAmount = 0): bool
    {
        if (!$this->is_active || !$this->is_approved) {
            return false;
        }

        // Check credit limit
        if ($this->credit_limit && $this->payment_terms === 'credit') {
            $newBalance = $this->current_balance + $orderAmount;
            if ($newBalance > $this->credit_limit) {
                return false;
            }
        }

        // Check minimum order
        if ($this->minimum_order_value && $orderAmount < $this->minimum_order_value) {
            return false;
        }

        // Check contract
        if (!$this->has_active_contract) {
            return false;
        }

        return true;
    }

    /**
     * Get products supplied as formatted list.
     */
    public function getFormattedProducts(): array
    {
        if (!$this->products_supplied) {
            return [];
        }

        return collect($this->products_supplied)->map(function ($product) {
            return [
                'name' => $product,
                'icon' => $this->getProductIcon($product),
            ];
        })->toArray();
    }

    /**
     * Get icon for product category.
     */
    private function getProductIcon(string $product): string
    {
        return match (strtolower($product)) {
            'detergent', 'soap', 'cleaner' => 'fa-soap',
            'fabric_softener' => 'fa-feather',
            'bleach' => 'fa-skull',
            'packaging' => 'fa-box',
            'equipment' => 'fa-gear',
            default => 'fa-tag',
        };
    }

    /**
     * Get performance summary.
     */
    public function getPerformanceSummary(): array
    {
        return [
            'rating' => $this->rating ?? 'N/A',
            'total_orders' => $this->total_orders,
            'total_spent' => number_format($this->total_spent, 2),
            'average_order_value' => $this->total_orders > 0
                ? round($this->total_spent / $this->total_orders, 2)
                : 0,
            'on_time_delivery' => $this->on_time_delivery_rate ? $this->on_time_delivery_rate . '%' : 'N/A',
            'quality_rating' => $this->quality_rating ?? 'N/A',
            'current_balance' => number_format($this->current_balance, 2),
            'available_credit' => $this->available_credit ? number_format($this->available_credit, 2) : 'N/A',
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

        // Auto-generate code if not provided
        static::creating(function ($supplier) {
            if (empty($supplier->code)) {
                $prefix = 'SUP';
                $count = static::withTrashed()->count() + 1;
                $supplier->code = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($supplier) {
            // If contract ended, maybe notify
            if (
                $supplier->isDirty('contract_end_date') &&
                $supplier->contract_end_date &&
                $supplier->contract_end_date->isPast()
            ) {
                // Trigger notification
                \Log::info("Contract ended for supplier: {$supplier->name}");
            }
        });

        static::deleting(function ($supplier) {
            // Check if there are pending purchases
            if ($supplier->purchases()->whereIn('status', ['pending', 'ordered'])->exists()) {
                throw new \Exception('Cannot delete supplier with pending purchases.');
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

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get all expenses related to this supplier.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'supplier_id');
    }

    /**
     * Get total paid to this supplier.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->expenses()->sum('amount');
    }

    /**
     * Get outstanding balance (if you track purchases).
     */
    public function getOutstandingBalanceAttribute(): float
    {
        // If you have purchases table
        $totalPurchases = $this->purchases()->sum('total_amount');
        $totalPaid = $this->total_paid;

        return $totalPurchases - $totalPaid;
    }
}
