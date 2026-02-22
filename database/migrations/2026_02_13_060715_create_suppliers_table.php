<?php
// database/migrations/2026_02_13_060720_create_suppliers_table.php

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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Branch Association (which branch does this supplier serve)
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('code')->unique(); // Supplier code (e.g., SUP001)
            $table->string('business_type')->default('individual'); // individual, company, manufacturer, distributor
            $table->string('tax_number')->nullable(); // VAT/PAYE number
            $table->string('registration_number')->nullable(); // Company registration

            // Contact Information
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            // Address Information
            $table->text('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Kenya');

            // Banking Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_swift_code')->nullable();
            $table->string('bank_sort_code')->nullable();

            // Payment Terms
            $table->enum('payment_terms', ['cash', 'bank_transfer', 'cheque', 'mpesa', 'credit'])->default('bank_transfer');
            $table->integer('payment_due_days')->default(30); // Net 30, Net 60, etc.
            $table->decimal('credit_limit', 12, 2)->nullable(); // Maximum credit allowed
            $table->decimal('current_balance', 12, 2)->default(0); // Current outstanding balance

            // Supply Details
            $table->json('products_supplied')->nullable(); // Categories or specific products
            $table->json('service_areas')->nullable(); // Areas they can deliver to
            $table->decimal('minimum_order_value', 10, 2)->nullable();
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->integer('lead_time_days')->nullable(); // Average delivery time

            // Contract Information
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->boolean('is_exclusive')->default(false); // Exclusive supplier?
            $table->string('contract_file')->nullable(); // Path to contract document

            // Performance Rating
            $table->decimal('rating', 3, 2)->nullable(); // 1-5 rating
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->decimal('on_time_delivery_rate', 5, 2)->nullable(); // Percentage
            $table->decimal('quality_rating', 3, 2)->nullable(); // 1-5 rating

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->uuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->json('documents')->nullable(); // Other documents

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('branch_id');
            $table->index('code');
            $table->index('name');
            $table->index('business_type');
            $table->index('is_active');
            $table->index('is_approved');
            $table->index('rating');
            $table->index('city');
            $table->index(['branch_id', 'is_active']);
            $table->index(['is_active', 'rating']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
