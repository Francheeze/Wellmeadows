<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_drug_items', function (Blueprint $table) {
            $table->integer('requisition_number');
            $table->integer('drug_number');
            $table->integer('quantity_required');
            $table->timestamps();

            $table->primary(['requisition_number', 'drug_number']);

            $table->foreign('requisition_number')
                  ->references('requisition_number')
                  ->on('requisitions')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('drug_number')
                  ->references('drug_number')
                  ->on('pharmaceutical_items')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_drug_items');
    }
};
