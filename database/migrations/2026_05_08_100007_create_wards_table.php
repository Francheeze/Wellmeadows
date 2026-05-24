<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->integer('ward_number')->primary();
            $table->string('ward_name');
            $table->string('location')->nullable();
            $table->integer('total_beds')->default(0);
            $table->string('telephone_extention')->nullable();
            $table->unsignedBigInteger('charge_nurse_number')->nullable();
            $table->timestamps();

            $table->foreign('charge_nurse_number')
                  ->references('staff_number')
                  ->on('staff')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};