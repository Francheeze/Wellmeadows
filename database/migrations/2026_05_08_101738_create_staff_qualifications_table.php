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
        Schema::create('staff_qualifications', function (Blueprint $table) {
    $table->id('qualificationID');
    // Foreign Key linking to Staff
    $table->foreignId('staffNumber')->constrained('staff', 'staffNumber')->onDelete('cascade');
    $table->string('type');
    $table->date('date');
    $table->string('institution');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_qualifications');
    }
};
