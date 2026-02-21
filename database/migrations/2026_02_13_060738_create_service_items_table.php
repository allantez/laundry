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
            $table->uuid('id')->primary();

            // Service Association
            $table->uuid('service_id');
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();

            // Branch Association
            $table->uuid('branch_id')->nullable();
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('code', 50)->nullable(); // 🔴 ADD LENGTH
            $table->text('description')->nullable();
            $table->string('short_description', 255)->nullable(); // 🔴 ADD LENGTH

            // Item Details - 🔴 ADD LENGTH LIMITS TO ALL
            $table->string('item_type', 50); // 🔴 LENGTH 50
            $table->string('fabric_type', 50)->nullable(); // 🔴 LENGTH 50
            $table->string('color', 30)->nullable(); // 🔴 LENGTH 30
            $table->string('size', 20)->nullable(); // 🔴 LENGTH 20

            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('minimum_charge', 10, 2)->nullable();
            $table->enum('pricing_model', ['fixed', 'per_item', 'per_set'])->default('fixed');
            $table->json('price_modifiers')->nullable();

            // Operational Settings
            $table->boolean('is_active')->default(true);
            $table->integer('estimated_duration')->nullable();
            $table->json('special_instructions')->nullable();

            // Inventory Integration
            $table->boolean('track_inventory')->default(false);
            $table->uuid('inventory_item_id')->nullable();
            $table->foreign('inventory_item_id')
                ->references('id')
                ->on('inventory_items')
                ->nullOnDelete();
            $table->decimal('inventory_quantity_per_unit', 10, 2)->nullable();

            // Display Options
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('requires_special_handling')->default(false);
            $table->decimal('special_handling_fee', 10, 2)->nullable();

            // Images
            $table->string('icon', 100)->nullable(); // 🔴 ADD LENGTH
            $table->string('image', 255)->nullable(); // 🔴 ADD LENGTH
            $table->json('gallery')->nullable();

            // Additional Info
            $table->json('care_instructions')->nullable();
            $table->json('restrictions')->nullable();
            $table->json('add_ons_available')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('service_id', 'si_service_idx');
            $table->index('branch_id', 'si_branch_idx');
            $table->index('item_type', 'si_type_idx');
            $table->index('is_active', 'si_active_idx');
            $table->index('is_popular', 'si_popular_idx');
            $table->index('sort_order', 'si_sort_idx');
            $table->index('code', 'si_code_idx');
            $table->index(['service_id', 'item_type', 'is_active'], 'si_service_type_active_idx');
            $table->index(['service_id', 'is_popular'], 'si_service_popular_idx');
            $table->index(['branch_id', 'is_active'], 'si_branch_active_idx');

            // 🔴 FIXED UNIQUE CONSTRAINT - Now within limits
            $table->unique(['service_id', 'item_type', 'size', 'fabric_type'], 'si_unique');
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
