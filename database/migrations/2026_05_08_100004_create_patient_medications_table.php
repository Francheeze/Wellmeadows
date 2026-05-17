<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: patient_number references the patients table managed by another team module.
     * Ensure the patients table migration runs BEFORE this one.
     * staffNumber and wardNumber in REQUISITION also belong to other modules.
     */
    public function up(): void
    {
        Schema::create('patient_medications', function (Blueprint $table) {
            // Composite PK: patientNumber + drugNumber + startDate
            $table->string('patient_number');
            $table->integer('drug_number');
            $table->date('start_date');
            $table->integer('units_per_day');
            $table->date('finish_date')->nullable();
            $table->timestamps();

            $table->primary(['patient_number', 'drug_number', 'start_date']);

            // FK to pharmaceutical_items (this module)
            $table->foreign('drug_number')
                  ->references('drug_number')
                  ->on('pharmaceutical_items')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            // FK to patients table — owned by another module.
            // Uncomment once the patients table exists in the project:
            // $table->foreign('patient_number')
            //       ->references('patient_number')
            //       ->on('patients')
            //       ->onUpdate('cascade')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medications');
    }
};
