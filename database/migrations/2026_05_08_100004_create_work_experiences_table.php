<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id('work_experience_id');
            $table->unsignedBigInteger('staff_number');
            $table->string('position');
            $table->string('organization');
            $table->date('start_date');
            $table->date('finish_date')->nullable();
            $table->timestamps();

            $table->foreign('staff_number')
                  ->references('staff_number')
                  ->on('staff')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};