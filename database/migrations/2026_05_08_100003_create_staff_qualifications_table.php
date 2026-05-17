<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->id('qualificationID');
            $table->unsignedBigInteger('staffNumber');
            $table->string('type'); // Degree, Diploma, Certificate, etc.
            $table->date('date'); // Date awarded/completed
            $table->string('institution');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('staffNumber')
                  ->references('staffNumber')
                  ->on('staff')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};