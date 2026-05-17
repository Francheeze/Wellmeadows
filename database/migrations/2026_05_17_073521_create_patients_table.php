<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->string('patient_number')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('telephone_number', 20);
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed', 'Separated']);
            $table->date('date_registered');
            $table->string('referred_by')->nullable(); // FK → local_doctors.clinic_number

            $table->timestamps();

            $table->foreign('referred_by')
                  ->references('clinic_number')
                  ->on('local_doctors')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
