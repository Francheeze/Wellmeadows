<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_supply_items', function (Blueprint $table) {
            $table->integer('requisition_number');
            $table->integer('item_number');
            $table->integer('quantity_required');
            $table->timestamps();

            $table->primary(['requisition_number', 'item_number']);

            $table->foreign('requisition_number')
                  ->references('requisition_number')
                  ->on('requisitions')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('item_number')
                  ->references('item_number')
                  ->on('supply_items')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_supply_items');
    }
};
