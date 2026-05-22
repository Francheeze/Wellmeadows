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
        Schema::create('beds', function (Blueprint $table) {
            $table->string('bednumber')->primary();
            $table->string('wardnumber');
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->timestamps();

            $table->foreign('wardnumber')
                  ->references('wardnumber')
                  ->on('wards')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};