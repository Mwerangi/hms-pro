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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            
            $table->string('medicine_name');
            $table->string('medicine_type')->nullable(); // tablet, capsule, syrup, injection, etc.
            $table->string('dosage'); // e.g., 500mg, 10ml
            $table->string('frequency'); // e.g., "3 times daily", "BD", "TDS", "QID"
            $table->string('duration'); // e.g., "7 days", "2 weeks"
            $table->integer('quantity'); // Total quantity to dispense
            $table->string('route')->nullable(); // oral, IV, IM, topical, etc.
            $table->text('instructions')->nullable(); // "After meals", "Before sleep", etc.
            
            $table->integer('quantity_dispensed')->default(0);
            $table->enum('status', ['pending', 'dispensed'])->default('pending');
            
            $table->timestamps();
            
            // Indexes
            $table->index('prescription_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
