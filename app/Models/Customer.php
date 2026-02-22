<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\CustomerFeedback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\HasUuid;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasUuid;

    /**
     * Mass Assignable
     */
    protected $fillable = [
        'branch_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'date_of_birth',
        'gender',
        'customer_type',
        'is_active',
        'is_verified',
        'verified_at',
        'verified_by',
        'loyalty_points',
        'total_orders',
        'total_spent',
        'last_order_date',
        'customer_since',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'delivery_instructions',
        'id_type',
        'id_number',
        'tax_number',
        'preferences',
        'tags',
        'notes',
        'password', // Keep only if customers authenticate
    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute Casting
     */
    protected function casts(): array
    {
        return [
            'date_of_birth'   => 'date',
            'verified_at'     => 'datetime',
            'last_order_date' => 'datetime',
            'customer_since'  => 'datetime',

            'is_active'   => 'boolean',
            'is_verified' => 'boolean',

            'loyalty_points' => 'integer',
            'total_orders'   => 'integer',
            'total_spent'    => 'float',

            'latitude'  => 'float',
            'longitude' => 'float',

            'preferences' => 'array',
            'tags'        => 'array',

            'password' => 'hashed',
        ];
    }

    /**
     * Default Attributes
     */
    protected $attributes = [
        'customer_type' => 'regular',
        'is_active'     => true,
        'is_verified'   => false,
        'loyalty_points' => 0,
        'total_orders'  => 0,
        'total_spent'   => 0,
        'country'       => 'Kenya',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    // =========================================================================
    // QUERY SCOPES
    // =========================================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeVip($query)
    {
        return $query->where('customer_type', 'vip');
    }

    public function scopeCorporate($query)
    {
        return $query->where('customer_type', 'corporate');
    }

    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('id_number', 'like', "%{$search}%");
        });
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->implode(', ');
    }

    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->mobile ?? $this->phone;
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        // Need to add: use Carbon\Carbon;
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getAverageRatingAttribute(): ?float
    {
        return $this->feedback_avg_rating ?? null;
    }

    public function getTotalFeedbackAttribute(): int
    {
        return $this->feedback_count ?? 0;
    }

    public function getLoyaltyTierAttribute(): array
    {
        return match (true) {
            $this->loyalty_points >= 5000 => ['name' => 'Platinum', 'discount' => 15],
            $this->loyalty_points >= 2000 => ['name' => 'Gold', 'discount' => 10],
            $this->loyalty_points >= 500  => ['name' => 'Silver', 'discount' => 5],
            default => ['name' => 'Bronze', 'discount' => 0],
        };
    }

    // =========================================================================
    // BUSINESS LOGIC
    // =========================================================================

    public function addLoyaltyPoints(int $points): self
    {
        $this->increment('loyalty_points', $points);
        return $this;
    }

    public function deductLoyaltyPoints(int $points): self
    {
        $this->loyalty_points = max(0, $this->loyalty_points - $points);
        $this->save();

        return $this;
    }

    public function verify(?User $verifiedBy = null): self
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifiedBy?->id,
        ]);

        return $this;
    }

    public function deactivate(?string $reason = null): self
    {
        $this->update([
            'is_active' => false,
            'notes' => $reason
                ? trim(($this->notes ?? '') . "\n\nDeactivated: {$reason}")
                : $this->notes,
        ]);

        return $this;
    }

    public function activate(): self
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Optimized Favorite Services Query (Scalable)
     */
    public function getFavoriteServices(int $limit = 5): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->where('orders.customer_id', $this->id)
            ->select('services.name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('total', 'name')
            ->toArray();
    }

    // =========================================================================
    // MODEL EVENTS
    // =========================================================================

    protected static function booted()
    {
        static::creating(function ($customer) {
            if (!$customer->customer_since) {
                $customer->customer_since = now();
            }
        });
    }

    // =========================================================================
    // NOTIFICATIONS
    // =========================================================================

    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }

    public function routeNotificationForSms(): ?string
    {
        return $this->mobile ?? $this->phone;
    }
}
