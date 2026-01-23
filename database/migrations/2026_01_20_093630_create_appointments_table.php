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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            
            // Patient Information
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            
            // Doctor Information
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Appointment Details
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('token_number', 10);
            $table->integer('queue_position')->nullable();
            
            // Appointment Type
            $table->enum('appointment_type', ['new', 'followup', 'emergency'])->default('new');
            
            // Status Management
            $table->enum('status', ['scheduled', 'waiting', 'in-consultation', 'completed', 'cancelled', 'no-show'])->default('scheduled');
            
            // Additional Information
            $table->text('reason_for_visit')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Timestamps
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('consultation_started_at')->nullable();
            $table->timestamp('consultation_ended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('appointment_number');
            $table->index('appointment_date');
            $table->index(['doctor_id', 'appointment_date']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
