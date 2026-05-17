<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('next_of_kins', function (Blueprint $table) {
            $table->string('next_of_kin_id')->primary();
            $table->string('patient_number');
            $table->string('full_name');
            $table->string('relationship');
            $table->text('address');
            $table->string('telephone_number', 20);
            $table->timestamps();

            $table->foreign('patient_number')
                  ->references('patient_number')
                  ->on('patients')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('next_of_kins');
    }
};
