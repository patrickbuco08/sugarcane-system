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
        Schema::create('harvest_batches', function (Blueprint $table) {
            $table->id();
            $table->string('label', 120)->nullable();  // e.g., "Week 34 - Field A"
            $table->date('week_of');                   // usually aligns with weekly_prices.week_of

            $table->foreignId('weekly_price_id')       // points to SRA price row
                  ->constrained('weekly_prices')
                  ->restrictOnDelete();                // prevent deleting a price in use

            $table->decimal('tons_harvested', 12, 3);  // metric tons
            $table->decimal('recovery_coeff', 5, 4)->default(0.09); // RC (editable)
            $table->decimal('farmers_share', 5, 4)->default(0.69);  // share (editable)

            $table->timestamps();

            $table->index('week_of');
            $table->index('weekly_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_batches');
    }
};
