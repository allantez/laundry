<?php
// database/migrations/2026_02_13_070000_create_customer_feedback_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Feedback Identification
            $table->string('feedback_number')->unique(); // Human-readable feedback number

            // Core Associations
            $table->uuid('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Service/Item Associations (optional - for specific service feedback)
            $table->uuid('service_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->uuid('service_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Staff Associations (feedback about specific staff)
            $table->uuid('staff_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Rating (1-5 scale)
            $table->tinyInteger('rating')->unsigned(); // 1 to 5
            $table->decimal('rating_score', 3, 2)->nullable(); // For weighted averages

            // Detailed Ratings (for specific aspects)
            $table->tinyInteger('quality_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('timeliness_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('staff_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('value_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('cleanliness_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('communication_rating')->unsigned()->nullable(); // 1-5

            // Feedback Content
            $table->text('comment')->nullable();
            $table->text('positive_feedback')->nullable(); // What they liked
            $table->text('negative_feedback')->nullable(); // What they didn't like
            $table->text('suggestions')->nullable(); // Suggestions for improvement

            // Categories/Tags
            $table->json('categories')->nullable(); // e.g., ['cleanliness', 'staff', 'speed']
            $table->json('tags')->nullable(); // For filtering/searching

            // Response/Resolution
            $table->text('staff_response')->nullable();
            $table->uuid('responded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('responded_at')->nullable();

            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->uuid('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->text('resolution_notes')->nullable();

            // Status
            $table->enum('status', [
                'pending',      // New feedback, not reviewed
                'reviewed',     // Reviewed by staff
                'actioned',     // Action taken
                'resolved',     // Issue resolved
                'archived',     // Archived
            ])->default('pending');

            // Visibility & Sharing
            $table->boolean('is_public')->default(false); // Can be shown on website
            $table->boolean('is_featured')->default(false); // Featured testimonial
            $table->boolean('is_anonymous')->default(false); // Hide customer name

            // Verification
            $table->boolean('is_verified')->default(false); // Verified as genuine
            $table->uuid('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            // Flags
            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();
            $table->uuid('flagged_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Source Information
            $table->enum('source', [
                'in_person',    // In-store feedback form
                'sms',          // Via SMS
                'email',        // Via email
                'web',          // Website form
                'mobile_app',   // Mobile app
                'social_media', // Social media
                'review_site',  // Google Reviews, Yelp, etc.
                'other'
            ])->default('in_person');

            $table->string('source_reference')->nullable(); // Link to external review

            // Follow-up
            $table->boolean('needs_followup')->default(false);
            $table->timestamp('followup_date')->nullable();
            $table->text('followup_notes')->nullable();

            // Satisfaction after resolution
            $table->tinyInteger('satisfaction_score')->unsigned()->nullable(); // 1-5 after resolution

            // Metadata
            $table->json('metadata')->nullable(); // Browser, device, location, etc.

            // User Associations
            $table->uuid('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->uuid('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('feedback_number');
            $table->index('customer_id');
            $table->index('order_id');
            $table->index('branch_id');
            $table->index('service_id');
            $table->index('staff_id');
            $table->index('rating');
            $table->index('status');
            $table->index('source');
            $table->index('is_public');
            $table->index('is_featured');
            $table->index('is_verified');
            $table->index('is_flagged');
            $table->index('needs_followup');
            $table->index('created_at');
            $table->index(['branch_id', 'rating']);
            $table->index(['branch_id', 'status']);
            $table->index(['branch_id', 'created_at']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['service_id', 'rating']);
            $table->index(['staff_id', 'rating']);
            $table->index(['rating', 'created_at']);
            $table->index('deleted_at');

            // Composite indexes for reporting
            $table->index(['branch_id', 'rating', 'created_at']);
            $table->index(['status', 'needs_followup']);
            $table->index(['is_resolved', 'resolved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
};
