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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('consultation_number')->unique();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Vitals
            $table->string('temperature')->nullable(); // e.g., 98.6°F
            $table->string('blood_pressure')->nullable(); // e.g., 120/80
            $table->string('pulse_rate')->nullable(); // e.g., 72 bpm
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->decimal('height', 5, 2)->nullable(); // cm
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('spo2')->nullable(); // oxygen saturation %
            $table->string('respiratory_rate')->nullable(); // breaths per minute
            
            // Consultation Details
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('social_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            
            // Examination
            $table->text('general_examination')->nullable();
            $table->text('systemic_examination')->nullable();
            $table->text('clinical_findings')->nullable();
            
            // Diagnosis & Treatment
            $table->text('provisional_diagnosis')->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->text('icd_codes')->nullable(); // Can store JSON array
            $table->text('treatment_plan')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->text('advice_instructions')->nullable();
            
            // Follow-up
            $table->date('next_visit_date')->nullable();
            $table->text('follow_up_instructions')->nullable();
            
            // Status
            $table->enum('status', ['in-progress', 'completed'])->default('in-progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('consultation_number');
            $table->index('appointment_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
