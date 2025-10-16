<?php

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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();

            $table->foreignId('harvest_batch_id')
                  ->nullable()                                // set to ->constrained(...)->cascadeOnDelete() if NOT nullable
                  ->constrained('harvest_batches')
                  ->nullOnDelete();                           // if batch is deleted, clear FK (or use cascade if you prefer)

            // add new column, position, its either bottom or top only
            $table->enum('position', ['bottom', 'top'])->nullable();

            $table->decimal('avg_brix', 6, 3);               // from AS7263+regression
            $table->decimal('pol', 6, 3)->nullable();

            $table->decimal('purity', 6, 3)->nullable();

            $table->string('label', 120)->nullable();

            // optional raw channels (for audit/debug)
            $table->integer('ch_r')->nullable();
            $table->integer('ch_s')->nullable();
            $table->integer('ch_t')->nullable();
            $table->integer('ch_u')->nullable();
            $table->integer('ch_v')->nullable();
            $table->integer('ch_w')->nullable();

            $table->decimal('sensor_temp_c', 5, 2)->nullable();
            $table->string('model_version', 64)->nullable();  // e.g., "brix_v1_2025_08"
            $table->string('coeff_hash', 100)->nullable();      // short hash for traceability

            $table->timestamps();

            $table->index('harvest_batch_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
