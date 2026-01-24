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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_code')->unique(); // MED-001, MED-002
            $table->string('medicine_name'); // Paracetamol
            $table->string('generic_name')->nullable(); // Acetaminophen
            $table->string('brand_name')->nullable(); // Panadol
            $table->enum('medicine_type', ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'ointment', 'drops', 'inhaler', 'suppository', 'other'])->default('tablet');
            $table->string('strength'); // 500mg, 10ml, 1g
            $table->string('manufacturer')->nullable();
            $table->text('description')->nullable();
            
            // Pricing
            $table->decimal('unit_price', 10, 2); // Price per unit (tablet/capsule/ml)
            $table->string('unit_of_measure')->default('piece'); // piece, ml, g, box
            
            // Inventory
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_level')->default(50);
            $table->date('expiry_date')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_prescription')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index('medicine_code');
            $table->index('medicine_name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
