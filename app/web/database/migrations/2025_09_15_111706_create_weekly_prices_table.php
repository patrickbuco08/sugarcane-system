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
        Schema::create('weekly_prices', function (Blueprint $table) {
            $table->id();
            $table->date('week_of')->unique();                 // e.g., 2025-08-17
            $table->decimal('b_domestic', 12, 2);              // ₱/LKG (required)
            $table->decimal('a_us_quota', 12, 2)->nullable();  // optional
            $table->decimal('molasses_mt', 12, 2)->nullable(); // optional
            $table->string('source', 120)->nullable();         // "SRA Weekly Millsite Prices"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_prices');
    }
};
