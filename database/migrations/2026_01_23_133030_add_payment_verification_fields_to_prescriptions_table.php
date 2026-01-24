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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->boolean('payment_verified')->default(false)->after('status');
            $table->foreignId('bill_id')->nullable()->constrained()->onDelete('set null')->after('payment_verified');
            $table->boolean('is_emergency')->default(false)->after('bill_id');
            $table->foreignId('emergency_approved_by')->nullable()->constrained('users')->onDelete('set null')->after('is_emergency');
            $table->text('emergency_reason')->nullable()->after('emergency_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['bill_id']);
            $table->dropForeign(['emergency_approved_by']);
            $table->dropColumn([
                'payment_verified',
                'bill_id',
                'is_emergency',
                'emergency_approved_by',
                'emergency_reason',
            ]);
        });
    }
};
