<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: staff_number references the staff table — owned by another module.
     * The FK is commented out. Uncomment once that table exists.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('appointment_number')->primary();
            $table->string('patient_number');
            $table->string('staff_number');   // FK → staff table (another module)
            $table->dateTime('date_time');
            $table->string('examination_room');
            $table->timestamps();

            $table->foreign('patient_number')
                  ->references('patient_number')
                  ->on('patients')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            // Uncomment once the staff table exists:
            // $table->foreign('staff_number')
            //       ->references('staff_number')
            //       ->on('staff')
            //       ->onUpdate('cascade')
            //       ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
