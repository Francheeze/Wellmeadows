<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->id('qualification_id');
            $table->string('staff_number');
            $table->string('type'); // Degree, Diploma, Certificate, etc.
            $table->date('date'); // Date awarded/completed
            $table->string('institution');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('staff_number')
                  ->references('staff_number')
                  ->on('staff')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};