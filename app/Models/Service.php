<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'code',
        'description',
        'short_description',
        'category',
        'sub_category',
        'tags',
        'pricing_type',
        'base_price',
        'minimum_charge',
        'price_tiers',
        'estimated_duration',
        'unit_type',
        'min_quantity',
        'max_quantity',
        'is_active',
        'is_visible_online',
        'requires_pickup',
        'requires_delivery',
        'is_express_available',
        'express_multiplier',
        'icon',
        'image',
        'gallery',
        'sort_order',
        'is_featured',
        'is_new',
        'has_discount',
        'discount_percentage',
        'discount_until',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'faqs',
        'instructions',
        'restrictions',
        'inclusions',
        'exclusions',
        'track_inventory',
        'inventory_item_id',
        'inventory_quantity_per_unit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'json',
            'price_tiers' => 'json',
            'gallery' => 'json',
            'faqs' => 'json',
            'instructions' => 'json',
            'restrictions' => 'json',
            'inclusions' => 'json',
            'exclusions' => 'json',
            'is_active' => 'boolean',
            'is_visible_online' => 'boolean',
            'requires_pickup' => 'boolean',
            'requires_delivery' => 'boolean',
            'is_express_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'has_discount' => 'boolean',
            'track_inventory' => 'boolean',
            'base_price' => 'decimal:2',
            'minimum_charge' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'express_multiplier' => 'decimal:2',
            'min_quantity' => 'decimal:2',
            'max_quantity' => 'decimal:2',
            'inventory_quantity_per_unit' => 'decimal:2',
            'discount_until' => 'datetime',
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
        'pricing_type' => 'fixed',
        'is_active' => true,
        'is_visible_online' => true,
        'requires_pickup' => false,
        'requires_delivery' => false,
        'is_express_available' => false,
        'express_multiplier' => 1.5,
        'min_quantity' => 1,
        'sort_order' => 0,
        'is_featured' => false,
        'is_new' => false,
        'has_discount' => false,
        'track_inventory' => false,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the branch that this service belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the service items for this service.
     */
    public function serviceItems(): HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }

    /**
     * Get the order items for this service.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the inventory item associated with this service.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive services.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include services visible online.
     */
    public function scopeVisibleOnline($query)
    {
        return $query->where('is_visible_online', true);
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
     * Scope a query to only include featured services.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include services with active discounts.
     */
    public function scopeOnDiscount($query)
    {
        return $query->where('has_discount', true)
            ->where(function ($q) {
                $q->whereNull('discount_until')
                    ->orWhere('discount_until', '>', now());
            });
    }

    /**
     * Scope a query to only include express-available services.
     */
    public function scopeExpressAvailable($query)
    {
        return $query->where('is_express_available', true);
    }

    /**
     * Scope a query to search services.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // =========================================================================
    // ACCESSORS & MUTATORS
    // =========================================================================

    /**
     * Get the service's full display name with code.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /**
     * Get the service's effective price (with discount applied).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->has_discount && $this->discount_percentage) {
            $discountAmount = ($this->base_price * $this->discount_percentage) / 100;
            return round($this->base_price - $discountAmount, 2);
        }

        return $this->base_price;
    }

    /**
     * Get the service's discount amount.
     */
    public function getDiscountAmountAttribute(): float
    {
        if ($this->has_discount && $this->discount_percentage) {
            return round(($this->base_price * $this->discount_percentage) / 100, 2);
        }

        return 0;
    }

    /**
     * Get the express price (if express is available).
     */
    public function getExpressPriceAttribute(): ?float
    {
        if ($this->is_express_available) {
            return round($this->effective_price * $this->express_multiplier, 2);
        }

        return null;
    }

    /**
     * Get formatted duration string.
     */
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->estimated_duration) {
            return 'Variable';
        }

        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            return "{$minutes} minute" . ($minutes > 1 ? 's' : '');
        }
    }

    /**
     * Get category badge color.
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'wash' => 'blue',
            'dry' => 'green',
            'iron' => 'orange',
            'fold' => 'purple',
            'dry_clean' => 'red',
            'special' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get status with color.
     */
    public function getStatusAttribute(): array
    {
        if (!$this->is_active) {
            return ['label' => 'Inactive', 'color' => 'red', 'icon' => 'fa-ban'];
        }

        if (!$this->is_visible_online) {
            return ['label' => 'Hidden', 'color' => 'orange', 'icon' => 'fa-eye-slash'];
        }

        return ['label' => 'Active', 'color' => 'green', 'icon' => 'fa-check-circle'];
    }

    /**
     * Get discount status with color.
     */
    public function getDiscountStatusAttribute(): ?array
    {
        if (!$this->has_discount || !$this->discount_percentage) {
            return null;
        }

        if ($this->discount_until && $this->discount_until->isPast()) {
            return ['label' => 'Expired', 'color' => 'red', 'icon' => 'fa-clock'];
        }

        return [
            'label' => "{$this->discount_percentage}% OFF",
            'color' => 'green',
            'icon' => 'fa-tag',
            'until' => $this->discount_until?->format('M d, Y'),
        ];
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Calculate price for a given quantity.
     */
    public function calculatePrice(float $quantity = 1, bool $express = false): float
    {
        // Validate quantity
        $quantity = max($this->min_quantity, $quantity);
        if ($this->max_quantity) {
            $quantity = min($this->max_quantity, $quantity);
        }

        $price = match ($this->pricing_type) {
            'fixed' => $this->effective_price,
            'per_unit' => $this->effective_price * $quantity,
            'per_weight' => $this->effective_price * $quantity,
            'per_item' => $this->effective_price * $quantity,
            default => $this->effective_price,
        };

        // Apply minimum charge
        if ($this->minimum_charge && $price < $this->minimum_charge) {
            $price = $this->minimum_charge;
        }

        // Apply express multiplier if needed
        if ($express && $this->is_express_available) {
            $price *= $this->express_multiplier;
        }

        return round($price, 2);
    }

    /**
     * Get price tiers for display.
     */
    public function getPriceTiersForDisplay(): array
    {
        if (!$this->price_tiers) {
            return [];
        }

        return collect($this->price_tiers)->map(function ($price, $quantity) {
            return [
                'quantity' => $quantity,
                'price' => $price,
                'savings' => $this->base_price - $price,
            ];
        })->toArray();
    }

    /**
     * Activate the service.
     */
    public function activate(): self
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Deactivate the service.
     */
    public function deactivate(): self
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Apply discount to service.
     */
    public function applyDiscount(float $percentage, ?string $until = null): self
    {
        $this->update([
            'has_discount' => true,
            'discount_percentage' => $percentage,
            'discount_until' => $until,
        ]);

        return $this;
    }

    /**
     * Remove discount from service.
     */
    public function removeDiscount(): self
    {
        $this->update([
            'has_discount' => false,
            'discount_percentage' => null,
            'discount_until' => null,
        ]);

        return $this;
    }

    /**
     * Check if service is available for a given quantity.
     */
    public function isQuantityAvailable(float $quantity): bool
    {
        if ($quantity < $this->min_quantity) {
            return false;
        }

        if ($this->max_quantity && $quantity > $this->max_quantity) {
            return false;
        }

        return true;
    }

    /**
     * Get inventory requirements for a given quantity.
     */
    public function getInventoryRequirements(float $quantity): ?array
    {
        if (!$this->track_inventory || !$this->inventory_item_id) {
            return null;
        }

        $requiredQuantity = $this->inventory_quantity_per_unit * $quantity;

        return [
            'item_id' => $this->inventory_item_id,
            'item_name' => $this->inventoryItem?->name,
            'required_quantity' => $requiredQuantity,
            'available_quantity' => $this->inventoryItem?->current_stock,
            'is_sufficient' => $this->inventoryItem &&
                $this->inventoryItem->current_stock >= $requiredQuantity,
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

        // Auto-generate slug and code if not provided
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }

            if (empty($service->code)) {
                $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $service->category), 0, 3));
                $count = static::where('category', $service->category)->count() + 1;
                $service->code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
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
