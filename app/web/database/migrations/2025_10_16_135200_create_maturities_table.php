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
        Schema::create('maturities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            
            $table->foreignId('top_sample_id')
                  ->nullable()
                  ->constrained('samples')
                  ->nullOnDelete();
            
            $table->foreignId('bottom_sample_id')
                  ->nullable()
                  ->constrained('samples')
                  ->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maturities');
    }
};
