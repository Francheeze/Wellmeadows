<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: staff_number references the staff table and ward_number references
     * the wards table — both managed by other team modules.
     * Ensure those migrations run BEFORE this one.
     */
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->string('requisition_number')->primary();
            $table->string('staff_number');
            $table->string('ward_number');
            $table->date('date_ordered');
            $table->timestamps();

            // FKs to other modules — uncomment once those tables exist:
            // $table->foreign('staff_number')
            //       ->references('staff_number')
            //       ->on('staff')
            //       ->onUpdate('cascade')
            //       ->onDelete('restrict');

            // $table->foreign('ward_number')
            //       ->references('ward_number')
            //       ->on('wards')
            //       ->onUpdate('cascade')
            //       ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
