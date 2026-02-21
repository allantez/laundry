<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_feedback';

    protected $fillable = [
        'feedback_number',
        'customer_id',
        'order_id',
        'branch_id',
        'service_id',
        'service_item_id',
        'staff_id',

        'rating',
        'rating_score',
        'quality_rating',
        'timeliness_rating',
        'staff_rating',
        'value_rating',
        'cleanliness_rating',
        'communication_rating',

        'comment',
        'positive_feedback',
        'negative_feedback',
        'suggestions',

        'categories',
        'tags',
        'metadata',

        'staff_response',
        'responded_by',
        'responded_at',

        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',

        'status',
        'is_public',
        'is_featured',
        'is_anonymous',
        'is_verified',
        'verified_by',
        'verified_at',

        'is_flagged',
        'flag_reason',
        'flagged_by',

        'source',
        'source_reference',

        'needs_followup',
        'followup_date',
        'followup_notes',

        'satisfaction_score',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
            'tags' => 'array',
            'metadata' => 'array',

            'rating' => 'integer',
            'rating_score' => 'float',

            'quality_rating' => 'integer',
            'timeliness_rating' => 'integer',
            'staff_rating' => 'integer',
            'value_rating' => 'integer',
            'cleanliness_rating' => 'integer',
            'communication_rating' => 'integer',

            'satisfaction_score' => 'integer',

            'responded_at' => 'datetime',
            'resolved_at' => 'datetime',
            'verified_at' => 'datetime',
            'followup_date' => 'datetime',

            'is_resolved' => 'boolean',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'is_anonymous' => 'boolean',
            'is_verified' => 'boolean',
            'is_flagged' => 'boolean',
            'needs_followup' => 'boolean',
        ];
    }

    protected $attributes = [
        'status' => 'pending',
        'is_public' => false,
        'is_featured' => false,
        'is_anonymous' => false,
        'is_verified' => false,
        'is_flagged' => false,
        'is_resolved' => false,
        'needs_followup' => false,
        'source' => 'in_person',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getRatingStarsAttribute(): string
    {
        return str_repeat('★', $this->rating ?? 0)
            . str_repeat('☆', 5 - ($this->rating ?? 0));
    }

    public function getCustomerNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->customer?->full_name ?? 'Unknown';
    }

    public function getTimeAgoAttribute(): ?string
    {
        return $this->created_at?->diffForHumans();
    }

    public function getAverageDetailedRatingAttribute(): ?float
    {
        $ratings = collect([
            $this->quality_rating,
            $this->timeliness_rating,
            $this->staff_rating,
            $this->value_rating,
            $this->cleanliness_rating,
            $this->communication_rating,
        ])->filter();

        return $ratings->isEmpty()
            ? null
            : round($ratings->avg(), 2);
    }

    // =====================================================
    // BUSINESS LOGIC
    // =====================================================

    /**
     * Concurrency-safe feedback number generator
     */
    public static function generateFeedbackNumber(): string
    {
        return DB::transaction(function () {

            $prefix = 'FB-' . now()->format('Ym');

            $last = self::where('feedback_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $next = 1;

            if ($last) {
                $lastNumber = (int) substr($last->feedback_number, -4);
                $next = $lastNumber + 1;
            }

            return $prefix . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

    public function respond(string $response, User $responder): self
    {
        $this->update([
            'staff_response' => $response,
            'responded_by' => $responder->id,
            'responded_at' => now(),
            'status' => 'reviewed',
        ]);

        return $this;
    }

    public function markAsResolved(User $resolver, ?string $notes = null): self
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $resolver->id,
            'resolution_notes' => $notes,
            'status' => 'resolved',
        ]);

        return $this;
    }

    public function verify(User $verifier): self
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifier->id,
        ]);

        return $this;
    }

    public function isPositive(): bool
    {
        return ($this->rating ?? 0) >= 4;
    }

    public function isNegative(): bool
    {
        return ($this->rating ?? 0) <= 2;
    }

    // =====================================================
    // MODEL EVENTS
    // =====================================================

    protected static function booted()
    {
        static::creating(function ($feedback) {

            if (empty($feedback->feedback_number)) {
                $feedback->feedback_number = self::generateFeedbackNumber();
            }

            if (empty($feedback->rating) && !empty($feedback->rating_score)) {
                $feedback->rating = round($feedback->rating_score);
            }

            if (Auth::check() && empty($feedback->created_by)) {
                $feedback->created_by = Auth::id();
            }
        });

        static::updating(function ($feedback) {
            if (Auth::check()) {
                $feedback->updated_by = Auth::id();
            }
        });
    }
}
