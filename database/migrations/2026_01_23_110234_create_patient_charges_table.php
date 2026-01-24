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
        Schema::create('patient_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            
            // Source tracking - what created this charge
            $table->string('source_type')->nullable(); // Appointment, LabOrder, Prescription, Admission, etc.
            $table->unsignedBigInteger('source_id')->nullable(); // ID of the source record
            
            // Pricing (locked at time of service delivery)
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // Price per unit at time of charge
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->boolean('taxable')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2); // Final amount for this charge
            
            // Status tracking
            $table->enum('status', ['pending', 'billed', 'cancelled'])->default('pending');
            $table->foreignId('bill_id')->nullable()->constrained()->onDelete('set null'); // Links to bill when billed
            
            // Audit trail
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('service_date')->useCurrent(); // When service was delivered
            $table->timestamp('billed_at')->nullable(); // When it was added to a bill
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['patient_id', 'status']);
            $table->index(['source_type', 'source_id']);
            $table->index('service_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_charges');
    }
};
