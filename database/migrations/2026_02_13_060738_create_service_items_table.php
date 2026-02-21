<?php
// database/migrations/2026_02_13_060710_create_service_items_table.php

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
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();

            // Service Association
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Branch Association (optional - can override parent service)
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('code')->nullable(); // Item code (e.g., WSH-SHIRT, DRY-SUIT)
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();

            // Item Details
            $table->string('item_type'); // shirt, pants, dress, suit, bedding, etc.
            $table->string('fabric_type')->nullable(); // cotton, silk, wool, synthetic, etc.
            $table->string('color')->nullable();
            $table->string('size')->nullable(); // S, M, L, XL, or numeric

            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('minimum_charge', 10, 2)->nullable();
            $table->enum('pricing_model', ['fixed', 'per_item', 'per_set'])->default('fixed');
            $table->json('price_modifiers')->nullable(); // For special handling (e.g., stain removal +$2)

            // Operational Settings
            $table->boolean('is_active')->default(true);
            $table->integer('estimated_duration')->nullable(); // In minutes (overrides service duration)
            $table->json('special_instructions')->nullable(); // Special handling instructions

            // Inventory Integration
            $table->boolean('track_inventory')->default(false);
            $table->foreignId('inventory_item_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('inventory_quantity_per_unit', 10, 2)->nullable();

            // Display Options
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('requires_special_handling')->default(false);
            $table->decimal('special_handling_fee', 10, 2)->nullable();

            // Images
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();

            // Additional Info
            $table->json('care_instructions')->nullable(); // Care labels and instructions
            $table->json('restrictions')->nullable(); // Items not accepted
            $table->json('add_ons_available')->nullable(); // Available add-ons for this item

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('service_id');
            $table->index('branch_id');
            $table->index('item_type');
            $table->index('is_active');
            $table->index('is_popular');
            $table->index('sort_order');
            $table->index('code');
            $table->index(['service_id', 'item_type', 'is_active']);
            $table->index(['service_id', 'is_popular']);
            $table->index(['branch_id', 'is_active']);

            // Unique constraint
            $table->unique(['service_id', 'item_type', 'size', 'fabric_type'], 'service_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_items');
    }
};
