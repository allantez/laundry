<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('key')->index();
            $table->text('value')->nullable();

            $table->uuid('branch_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->unique(['key', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
