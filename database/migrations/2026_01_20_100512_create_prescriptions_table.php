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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            $table->date('prescription_date');
            $table->date('valid_until')->nullable(); // Prescription validity
            
            $table->text('special_instructions')->nullable();
            $table->text('pharmacy_notes')->nullable();
            
            $table->enum('status', ['pending', 'partially-dispensed', 'dispensed', 'cancelled'])->default('pending');
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dispensed_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('prescription_number');
            $table->index('consultation_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('status');
            $table->index('prescription_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
