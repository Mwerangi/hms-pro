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
        Schema::create('patient_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('utilized_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2);
            $table->dateTime('deposit_date');
            $table->enum('payment_method', ['cash', 'card', 'upi', 'bank_transfer', 'cheque', 'other']);
            $table->string('payment_reference')->nullable();
            $table->foreignId('received_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->dateTime('refund_date')->nullable();
            $table->foreignId('refunded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('patient_deposits');
    }
};
