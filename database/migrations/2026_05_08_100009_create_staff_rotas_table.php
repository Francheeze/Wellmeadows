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
    Schema::create('staff_rotas', function (Blueprint $table) {
        $table->string('wardnumber');
        $table->string('staffnumber');
        $table->enum('shift', ['Early', 'Late', 'Night']);
        $table->date('weekstartdate');
        $table->timestamps();

        $table->foreign('wardnumber')
              ->references('wardnumber')
              ->on('wards')
              ->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_rotas');
    }
};
