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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->enum('bill_type', ['opd', 'ipd', 'emergency', 'pharmacy', 'laboratory', 'final']);
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('visit_type', ['opd', 'ipd', 'emergency'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // appointment_id or admission_id
            $table->dateTime('bill_date');
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->string('discount_reason')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'credit'])->default('unpaid');
            $table->decimal('insurance_claim_amount', 10, 2)->default(0);
            $table->decimal('patient_payable', 10, 2)->default(0);
            $table->foreignId('billed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'finalized', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
