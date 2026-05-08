<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('item_number')->primary();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_level');
            $table->decimal('cost_per_unit', 10, 2);
            $table->string('supplier_number');
            $table->timestamps();

            $table->foreign('supplier_number')
                  ->references('supplier_number')
                  ->on('suppliers')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
