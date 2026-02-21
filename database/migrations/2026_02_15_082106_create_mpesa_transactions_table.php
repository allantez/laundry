<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('payment_id')->nullable()->constrained()->nullOnDelete();

            $table->string('merchant_request_id')->nullable()->index();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('mpesa_receipt_number')->nullable()->index();

            $table->string('phone_number');
            $table->decimal('amount', 12, 2);

            $table->enum('status', [
                'pending',
                'success',
                'failed'
            ])->default('pending')->index();

            $table->json('raw_payload')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_id');
            $table->index('payment_id');
            $table->index('phone_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
