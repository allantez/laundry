<?php
// database/migrations/2026_02_13_060738_create_service_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Service Association
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // 🔴 FIX: First create the branch_id column
            $table->unsignedBigInteger('branch_id')->nullable(); // Use unsignedBigInteger to match branches.id

            // Basic Information
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('code', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('short_description', 255)->nullable();

            // Item Details
            $table->string('item_type', 50);
            $table->string('fabric_type', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->string('size', 20)->nullable();

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
            $table->foreignId('inventory_item_id')
                ->nullable()
                ->constrained('inventory_items')
                ->nullOnDelete();
            $table->decimal('inventory_quantity_per_unit', 10, 2)->nullable();

            // Display Options
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('requires_special_handling')->default(false);
            $table->decimal('special_handling_fee', 10, 2)->nullable();

            // Images
            $table->string('icon', 100)->nullable();
            $table->string('image', 255)->nullable();
            $table->json('gallery')->nullable();

            // Additional Info
            $table->json('care_instructions')->nullable();
            $table->json('restrictions')->nullable();
            $table->json('add_ons_available')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // 🔴 FIX: Add foreign key constraint AFTER column is created
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->nullOnDelete();

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

            // Unique constraint
            $table->unique(['service_id', 'item_type', 'size', 'fabric_type'], 'si_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_items');
    }
};
