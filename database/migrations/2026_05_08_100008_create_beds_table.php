<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->integer('bed_number')->primary();
            $table->integer('ward_number');
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->timestamps();

            $table->foreign('ward_number')
                  ->references('ward_number')
                  ->on('wards')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};