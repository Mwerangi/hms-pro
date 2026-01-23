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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique(); // ADM2026XXXXX
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('bed_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // Admitting doctor
            $table->foreignId('admitted_by')->constrained('users')->onDelete('cascade'); // User who created admission
            
            // Admission details
            $table->timestamp('admission_date');
            $table->enum('admission_type', ['emergency', 'elective', 'transfer', 'delivery'])->default('elective');
            $table->enum('admission_category', ['medical', 'surgical', 'maternity', 'pediatric', 'icu'])->default('medical');
            $table->text('reason_for_admission');
            $table->text('provisional_diagnosis')->nullable();
            $table->text('complaints')->nullable();
            $table->text('medical_history')->nullable();
            
            // Vital signs at admission
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable();
            
            // Insurance & billing
            $table->enum('payment_mode', ['cash', 'insurance', 'company', 'government'])->default('cash');
            $table->string('insurance_company')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->decimal('estimated_stay_days', 5, 1)->nullable();
            $table->decimal('advance_payment', 10, 2)->default(0);
            
            // Status tracking
            $table->enum('status', ['admitted', 'transferred', 'discharged', 'deceased'])->default('admitted');
            $table->timestamp('discharge_date')->nullable();
            $table->foreignId('discharged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('discharge_summary')->nullable();
            $table->text('discharge_instructions')->nullable();
            $table->text('follow_up_instructions')->nullable();
            
            // Emergency contact (at time of admission)
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            
            $table->text('admission_notes')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
