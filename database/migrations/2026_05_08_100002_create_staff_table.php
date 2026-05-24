<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->string('staff_number')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('telephone_number');
            $table->date('date_of_birth');
            $table->char('sex', 1);
            $table->string('nin')->unique();
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('position');
            $table->decimal('current_salary', 10, 2);
            $table->string('salary_scale');
            $table->integer('hours_per_week');
            $table->string('contract_type');
            $table->string('payment_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};