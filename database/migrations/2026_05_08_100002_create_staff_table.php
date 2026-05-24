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
       Schema::create('staff', function (Blueprint $table) {
    $table->string('staff_number')->primary();
    $table->string('first_name');
    $table->string('last_name');
    $table->text('address');
    $table->string('telephone_number');
    $table->date('date_of_birth');
    $table->char('sex', 1);
    $table->string('nin')->unique(); // National Insurance Number
    $table->string('position');
    $table->decimal('current_salary', 10, 2);
    $table->string('salary_scale');
    $table->integer('hours_per_week');
    $table->string('contract_type');
    $table->string('payment_type');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
