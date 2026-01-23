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
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('patient_type', ['scheduled', 'walk-in', 'emergency'])->default('scheduled')->after('appointment_type');
            $table->integer('priority_order')->default(0)->after('queue_position')->comment('Lower number = higher priority. Emergency=1, Scheduled=2, Walk-in=3');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->after('vitals_recorded_by')->comment('Nurse who assigned doctor');
            $table->timestamp('doctor_assigned_at')->nullable()->after('assigned_by');
            $table->text('chief_complaint_initial')->nullable()->after('reason_for_visit')->comment('Initial complaint noted at registration/nursing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn([
                'patient_type',
                'priority_order',
                'assigned_by',
                'doctor_assigned_at',
                'chief_complaint_initial',
            ]);
        });
    }
};
