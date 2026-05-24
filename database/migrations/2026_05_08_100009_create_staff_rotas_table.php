<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_rotas', function (Blueprint $table) {
            $table->string('ward_number');
            $table->unsignedBigInteger('staff_number');
            $table->enum('shift', ['Early', 'Late', 'Night']);
            $table->date('week_start_date');
            $table->timestamps();

            $table->foreign('ward_number')
                  ->references('ward_number')
                  ->on('wards')
                  ->onDelete('cascade');

            $table->foreign('staff_number')
                  ->references('staffNumber')
                  ->on('staff')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_rotas');
    }
};