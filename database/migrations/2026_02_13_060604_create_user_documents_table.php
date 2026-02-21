<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('document_type');
            $table->string('document_number')->nullable();
            $table->string('file_path');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);

            // 🔴 FIX: Remove verified_by foreign key from this migration
            // Add it in a separate migration after users table is fully set up
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('document_type');
            $table->index('is_verified');
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
