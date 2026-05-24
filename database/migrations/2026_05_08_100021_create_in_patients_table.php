<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * IN PATIENT has appointment_number as its PK (and FK to appointments).
     * ward_number and bed_number reference tables owned by another module.
     * Those FKs are commented out — uncomment when teammates' tables exist.
     */
    public function up(): void
    {
        Schema::create('in_patients', function (Blueprint $table) {
            $table->integer('appointment_number')->primary();
            $table->string('patient_number');
            $table->integer('ward_number');   // FK → wards table (another module)
            $table->integer('bed_number');    // FK → beds table (another module)
            $table->integer('expected_stay'); // in days
            $table->date('date_placed');
            $table->date('date_leave')->nullable();
            $table->date('actual_leave')->nullable();
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

            // FK to wards — uncomment once ward table exists:
            $table->foreign('ward_number')
                   ->references('ward_number')
                   ->on('wards')
                   ->onUpdate('cascade')
                   ->onDelete('restrict');

            // FK to beds — uncomment once bed table exists:
            $table->foreign('bed_number')
                   ->references('bed_number')
                   ->on('beds')
                   ->onUpdate('cascade')
                   ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_patients');
    }
};
