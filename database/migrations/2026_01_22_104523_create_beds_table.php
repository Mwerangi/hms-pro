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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->string('bed_number'); // e.g., B-001, ICU-BED-05
            $table->string('bed_label')->nullable(); // Human-friendly name like "Bed A1"
            $table->enum('bed_type', ['standard', 'electric', 'icu', 'nicu', 'isolation'])->default('standard');
            $table->enum('status', ['available', 'occupied', 'under_cleaning', 'maintenance', 'reserved'])->default('available');
            $table->text('features')->nullable(); // JSON or text: oxygen, ventilator, monitor, etc.
            $table->boolean('has_oxygen')->default(false);
            $table->boolean('has_ventilator')->default(false);
            $table->boolean('has_monitor')->default(false);
            $table->decimal('additional_charge_per_day', 10, 2)->default(0); // Extra charge beyond ward base
            $table->text('notes')->nullable();
            $table->timestamp('last_cleaned_at')->nullable();
            $table->timestamp('occupied_since')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['ward_id', 'bed_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
