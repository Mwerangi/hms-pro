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
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
            $table->string('batch_number');
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('quantity_received');
            $table->integer('quantity_remaining')->default(0);
            $table->timestamps();
            
            $table->unique(['item_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
