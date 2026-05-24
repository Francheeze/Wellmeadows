<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * OUT PATIENT has appointment_number as its PK (and FK to appointments).
     * A patient is classified as out-patient after their exam result is confirmed.
     */
    public function up(): void
    {
        Schema::create('out_patients', function (Blueprint $table) {
            $table->integer('appointment_number')->primary();
            $table->string('patient_number');
            $table->dateTime('appointment_date_time');
            $table->timestamps();

            // FK to appointments (this module)
            $table->foreign('appointment_number')
                  ->references('appointment_number')
                  ->on('appointments')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            // FK to patients (this module)
            $table->foreign('patient_number')
                  ->references('patient_number')
                  ->on('patients')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('out_patients');
    }
};
