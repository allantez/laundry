<?php
// app/Models/ServiceItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class ServiceItem extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'branch_id',
        'name',
        'slug',
        'code',
        'description',
        'short_description',
        'item_type',
        'fabric_type',
        'color',
        'size',
        'base_price',
        'minimum_charge',
        'pricing_model',
        'price_modifiers',
        'is_active',
        'estimated_duration',
        'special_instructions',
        'track_inventory',
        'inventory_item_id',
        'inventory_quantity_per_unit',
        'sort_order',
        'is_popular',
        'requires_special_handling',
        'special_handling_fee',
        'icon',
        'image',
        'gallery',
        'care_instructions',
        'restrictions',
        'add_ons_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_modifiers' => 'json',
            'special_instructions' => 'json',
            'gallery' => 'json',
            'care_instructions' => 'json',
            'restrictions' => 'json',
            'add_ons_available' => 'json',
            'is_active' => 'boolean',
            'track_inventory' => 'boolean',
            'is_popular' => 'boolean',
            'requires_special_handling' => 'boolean',
            'base_price' => 'decimal:2',
            'minimum_charge' => 'decimal:2',
            'special_handling_fee' => 'decimal:2',
            'inventory_quantity_per_unit' => 'decimal:2',
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
        'pricing_model' => 'fixed',
        'is_active' => true,
        'is_popular' => false,
        'requires_special_handling' => false,
        'track_inventory' => false,
        'sort_order' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the service that this item belongs to.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the branch that this item belongs to (if branch-specific).
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the order items for this service item.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the inventory item associated with this service item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive items.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to filter by service.
     */
    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->where('branch_id', $branchId)
                ->orWhereNull('branch_id');
        });
    }

    /**
     * Scope a query to filter by item type.
     */
    public function scopeOfType($query, string $itemType)
    {
        return $query->where('item_type', $itemType);
    }

    /**
     * Scope a query to filter by fabric type.
     */
    public function scopeWithFabric($query, string $fabricType)
    {
        return $query->where('fabric_type', $fabricType);
    }

    /**
     * Scope a query to filter by size.
     */
    public function scopeWithSize($query, string $size)
    {
        return $query->where('size', $size);
    }

    /**
     * Scope a query to only include popular items.
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope a query to only include items requiring special handling.
     */
    public function scopeSpecialHandling($query)
    {
        return $query->where('requires_special_handling', true);
    }

    /**
     * Scope a query to search items.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('item_type', 'like', "%{$search}%")
                ->orWhere('fabric_type', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
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
     * Get the item's full display name with service.
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [$this->name];

        if ($this->size) {
            $parts[] = "Size: {$this->size}";
        }

        if ($this->fabric_type) {
            $parts[] = ucfirst($this->fabric_type);
        }

        return implode(' - ', $parts);
    }

    /**
     * Get the effective price (including special handling fee).
     */
    public function getEffectivePriceAttribute(): float
    {
        $price = $this->base_price;

        if ($this->requires_special_handling && $this->special_handling_fee) {
            $price += $this->special_handling_fee;
        }

        return round($price, 2);
    }

    /**
     * Get status with color.
     */
    public function getStatusAttribute(): array
    {
        if (!$this->is_active) {
            return ['label' => 'Inactive', 'color' => 'red', 'icon' => 'fa-ban'];
        }

        if ($this->requires_special_handling) {
            return ['label' => 'Special Handling', 'color' => 'orange', 'icon' => 'fa-exclamation-triangle'];
        }

        return ['label' => 'Active', 'color' => 'green', 'icon' => 'fa-check-circle'];
    }

    /**
     * Get item type label with formatting.
     */
    public function getItemTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->item_type));
    }

    /**
     * Get fabric type label with formatting.
     */
    public function getFabricTypeLabelAttribute(): string
    {
        return $this->fabric_type ? ucwords($this->fabric_type) : 'Any';
    }

    /**
     * Get size label with formatting.
     */
    public function getSizeLabelAttribute(): string
    {
        return $this->size ? strtoupper($this->size) : 'One Size';
    }

    /**
     * Check if item has price modifiers.
     */
    public function getHasPriceModifiersAttribute(): bool
    {
        return !empty($this->price_modifiers);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    /**
     * Calculate price for a given quantity with optional modifiers.
     */
    public function calculatePrice(float $quantity = 1, array $modifiers = []): float
    {
        $price = match ($this->pricing_model) {
            'fixed' => $this->effective_price,
            'per_item' => $this->effective_price * $quantity,
            'per_set' => $this->effective_price, // Sets are priced as one unit
            default => $this->effective_price * $quantity,
        };

        // Apply minimum charge
        if ($this->minimum_charge && $price < $this->minimum_charge) {
            $price = $this->minimum_charge;
        }

        // Apply price modifiers
        if (!empty($modifiers) && $this->price_modifiers) {
            foreach ($modifiers as $modifier) {
                if (isset($this->price_modifiers[$modifier])) {
                    $price += $this->price_modifiers[$modifier];
                }
            }
        }

        return round($price, 2);
    }

    /**
     * Get available price modifiers for this item.
     */
    public function getAvailableModifiers(): array
    {
        if (!$this->price_modifiers) {
            return [];
        }

        return collect($this->price_modifiers)->map(function ($price, $key) {
            return [
                'key' => $key,
                'label' => ucwords(str_replace('_', ' ', $key)),
                'price' => $price,
            ];
        })->values()->toArray();
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

    /**
     * Activate the item.
     */
    public function activate(): self
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Deactivate the item.
     */
    public function deactivate(): self
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Check if item is available for order.
     */
    public function isAvailableForOrder(float $quantity = 1): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->track_inventory && $this->inventory_item_id) {
            $requirements = $this->getInventoryRequirements($quantity);
            return $requirements && $requirements['is_sufficient'];
        }

        return true;
    }

    /**
     * Get care instructions as formatted list.
     */
    public function getFormattedCareInstructions(): array
    {
        if (!$this->care_instructions) {
            return [];
        }

        return collect($this->care_instructions)->map(function ($instruction) {
            return [
                'icon' => $this->getCareIcon($instruction),
                'text' => $instruction,
            ];
        })->toArray();
    }

    /**
     * Get icon for care instruction.
     */
    private function getCareIcon(string $instruction): string
    {
        return match (true) {
            str_contains(strtolower($instruction), 'wash') => 'fa-soap',
            str_contains(strtolower($instruction), 'dry') => 'fa-wind',
            str_contains(strtolower($instruction), 'iron') => 'fa-temperature-high',
            str_contains(strtolower($instruction), 'bleach') => 'fa-skull',
            default => 'fa-info-circle',
        };
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
        static::creating(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name . '-' . ($item->size ?? '') . '-' . ($item->fabric_type ?? ''));
            }

            if (empty($item->code)) {
                $service = Service::find($item->service_id);
                $prefix = $service ? substr($service->code, 0, 3) : 'ITM';
                $count = static::where('service_id', $item->service_id)->count() + 1;
                $item->code = $prefix . '-ITM' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($item) {
            if ($item->isDirty('is_active') && !$item->is_active) {
                // Log deactivation or perform any cleanup
                \Log::info("Service item {$item->id} deactivated");
            }
        });

        static::deleting(function ($item) {
            // Check if there are any pending orders using this item
            if ($item->orderItems()->whereHas('order', function ($q) {
                $q->whereIn('status', ['pending', 'processing']);
            })->exists()) {
                throw new \Exception('Cannot delete service item with pending orders.');
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
                    $builder->where(function ($q) use ($branchIds) {
                        $q->whereIn('branch_id', $branchIds)
                            ->orWhereNull('branch_id'); // Include global items
                    });
                }
            }
        });
    }
}
