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
       Schema::create('staff', function (Blueprint $table) {
    $table->id('staffNumber'); // Primary Key
    $table->string('firstName');
    $table->string('lastName');
    $table->text('address');
    $table->string('telephoneNumber');
    $table->date('dateOfBirth');
    $table->char('sex', 1);
    $table->string('NIN')->unique(); // National Insurance Number
    $table->string('position');
    $table->decimal('currentSalary', 10, 2);
    $table->string('salaryScale');
    $table->integer('hoursPerWeek');
    $table->string('contractType');
    $table->string('paymentType');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
