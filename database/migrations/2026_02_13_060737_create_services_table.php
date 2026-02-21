<?php
// database/migrations/2026_02_13_060700_create_services_table.php

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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Branch Association (if services are branch-specific)
            $table->uuid('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // Service code (e.g., WSH001, DRY002)
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();

            // Categorization
            $table->string('category'); // wash, dry, iron, fold, dry_clean, special
            $table->string('sub_category')->nullable();
            $table->json('tags')->nullable(); // For filtering/searching

            // Pricing Model
            $table->enum('pricing_type', ['fixed', 'per_unit', 'per_weight', 'per_item'])->default('fixed');
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('minimum_charge', 10, 2)->nullable(); // Minimum order value
            $table->json('price_tiers')->nullable(); // For bulk pricing

            // Service Details
            $table->integer('estimated_duration')->nullable(); // In minutes
            $table->enum('unit_type', ['piece', 'kg', 'set', 'bundle'])->nullable();
            $table->decimal('min_quantity', 8, 2)->default(1); // Minimum order quantity
            $table->decimal('max_quantity', 8, 2)->nullable(); // Maximum order quantity

            // Operational Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible_online')->default(true);
            $table->boolean('requires_pickup')->default(false);
            $table->boolean('requires_delivery')->default(false);
            $table->boolean('is_express_available')->default(false);
            $table->decimal('express_multiplier', 3, 2)->default(1.5); // 1.5x price for express

            // Media & Assets
            $table->string('icon')->nullable(); // FontAwesome or custom icon
            $table->string('image')->nullable(); // Main service image
            $table->json('gallery')->nullable(); // Additional images

            // Display Options
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('has_discount')->default(false);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->timestamp('discount_until')->nullable();

            // SEO & Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Additional Info
            $table->json('faqs')->nullable(); // Frequently asked questions
            $table->json('instructions')->nullable(); // Special instructions
            $table->json('restrictions')->nullable(); // Items not accepted
            $table->json('inclusions')->nullable(); // What's included
            $table->json('exclusions')->nullable(); // What's not included

            // Inventory Integration
            $table->boolean('track_inventory')->default(false);
            $table->uuid('inventory_item_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('inventory_quantity_per_unit', 10, 2)->nullable(); // e.g., 0.5L detergent per kg

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('branch_id');
            $table->index('category');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('sort_order');
            $table->index('slug');
            $table->index('code');
            $table->index(['branch_id', 'category', 'is_active']);
            $table->index(['is_active', 'is_visible_online']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
