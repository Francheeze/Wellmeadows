<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * EXAM RESULT has a 1-to-1 relationship with APPOINTMENT.
     * appointment_number is both the PK and FK — one result per appointment.
     */
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->string('appointment_number')->primary();
            $table->enum('result', ['Out-patient', 'WaitingList']);
            $table->date('examined_date');
            $table->timestamps();

            $table->foreign('appointment_number')
                  ->references('appointment_number')
                  ->on('appointments')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
