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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
            $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
            $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
            $table->enum('transaction_type', ['in', 'out', 'transfer', 'adjustment']);
            $table->integer('quantity');
            $table->integer('balance_after');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('from_location_id')->nullable()->constrained('stock_locations')->onDelete('set null');
            $table->foreignId('to_location_id')->nullable()->constrained('stock_locations')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
