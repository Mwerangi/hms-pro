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
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('test_type', ['blood', 'urine', 'stool', 'imaging', 'pathology', 'other'])->default('blood');
            $table->string('test_category')->nullable(); // Hematology, Biochemistry, Radiology, etc.
            $table->text('tests_ordered'); // JSON array of test names or comma-separated
            $table->text('clinical_notes')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->enum('urgency', ['routine', 'urgent', 'stat'])->default('routine');
            $table->enum('status', [
                'pending',
                'sample-collected',
                'in-progress',
                'completed',
                'reported',
                'cancelled'
            ])->default('pending');
            
            $table->date('order_date');
            $table->timestamp('sample_collected_at')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('reported_at')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('results')->nullable(); // Can be text or file path
            $table->text('lab_technician_notes')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('order_number');
            $table->index('consultation_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('test_type');
            $table->index('status');
            $table->index('urgency');
            $table->index('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
