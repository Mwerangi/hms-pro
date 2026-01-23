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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            
            // Unique Patient ID
            $table->string('patient_id', 20)->unique();
            
            // Demographics
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group', 10)->nullable();
            $table->string('marital_status', 20)->nullable();
            
            // Contact Information
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('Kenya');
            
            // Emergency Contact
            $table->string('emergency_contact_name', 100);
            $table->string('emergency_contact_phone', 20);
            $table->string('emergency_contact_relationship', 50);
            
            // Medical Information
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('medical_history')->nullable();
            
            // Additional Information
            $table->string('occupation', 100)->nullable();
            $table->string('insurance_provider', 100)->nullable();
            $table->string('insurance_number', 100)->nullable();
            $table->string('photo')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for fast search
            $table->index('patient_id');
            $table->index('phone');
            $table->index(['first_name', 'last_name']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
