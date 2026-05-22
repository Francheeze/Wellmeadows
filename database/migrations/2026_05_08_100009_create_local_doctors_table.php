<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_doctors', function (Blueprint $table) {
            $table->string('clinic_number')->primary();
            $table->string('full_name');
            $table->string('address');
            $table->string('telephone_number', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_doctors');
    }
};
