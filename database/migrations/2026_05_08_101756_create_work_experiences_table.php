<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id('workExperienceID');
            $table->unsignedBigInteger('staffNumber');
            $table->string('position');
            $table->string('organization');
            $table->date('startDate');
            $table->date('finishDate')->nullable(); // Nullable for current job
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
        Schema::dropIfExists('work_experiences');
    }
};