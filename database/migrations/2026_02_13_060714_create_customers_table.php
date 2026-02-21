<?php
// database/migrations/2026_02_13_060650_create_customers_table.php

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Branch Association (Critical for multi-branch)
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // Customer Type & Status
            $table->enum('customer_type', ['regular', 'vip', 'corporate', 'staff'])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            // Loyalty & Points
            $table->integer('loyalty_points')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->date('last_order_date')->nullable();
            $table->date('customer_since')->nullable();

            // Address Information
            $table->text('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Kenya');

            // Location (for delivery)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('delivery_instructions')->nullable();

            // Identification
            $table->string('id_type')->nullable(); // national_id, passport, drivers_license
            $table->string('id_number')->nullable();
            $table->string('tax_number')->nullable(); // VAT/PAYE number for corporate

            // Preferences
            $table->json('preferences')->nullable(); // Preferred services, communication preferences
            $table->json('tags')->nullable(); // Customer tags/categories
            $table->text('notes')->nullable();

            // Account Management
            $table->string('password')->nullable(); // If customer can login to track orders
            $table->rememberToken();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['first_name', 'last_name']);
            $table->index('phone');
            $table->index('mobile');
            $table->index('customer_type');
            $table->index('is_active');
            $table->index('loyalty_points');
            $table->index('total_spent');
            $table->index('last_order_date');
            $table->index('city');
            $table->index('id_number');
            $table->index(['branch_id', 'is_active']);
            $table->index(['branch_id', 'customer_type']);
            $table->index('deleted_at');

            // Unique constraints
            $table->unique(['email', 'branch_id']); // Email unique per branch
            $table->unique(['phone', 'branch_id']); // Phone unique per branch
            $table->unique(['id_number', 'branch_id'])->nullable(); // ID unique per branch
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
