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
        Schema::create('stock_issue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_issue_id')->constrained('stock_issues')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
            $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
            $table->integer('quantity');
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issue_items');
    }
};
