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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('item_categories')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('unit')->default('pieces');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->integer('reorder_level')->default(10);
            $table->boolean('requires_batch_tracking')->default(false);
            $table->boolean('is_medicine')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
