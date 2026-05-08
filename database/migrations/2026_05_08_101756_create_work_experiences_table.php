<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('work_experiences', function (Blueprint $table) {
    $table->id('workExperienceID');
    $table->foreignId('staffNumber')->constrained('staff', 'staffNumber')->onDelete('cascade');
    $table->string('position');
    $table->string('organization');
    $table->date('startDate');
    $table->date('finishDate');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
