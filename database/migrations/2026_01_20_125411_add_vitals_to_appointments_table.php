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
            // Vital Signs (recorded by nursing station)
            $table->string('blood_pressure')->nullable()->after('reason_for_visit');
            $table->string('pulse_rate')->nullable()->after('blood_pressure');
            $table->string('temperature')->nullable()->after('pulse_rate');
            $table->string('respiratory_rate')->nullable()->after('temperature');
            $table->string('spo2')->nullable()->after('respiratory_rate');
            $table->string('weight')->nullable()->after('spo2');
            $table->string('height')->nullable()->after('weight');
            $table->timestamp('vitals_recorded_at')->nullable()->after('checked_in_at');
            $table->foreignId('vitals_recorded_by')->nullable()->constrained('users')->after('vitals_recorded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['vitals_recorded_by']);
            $table->dropColumn([
                'blood_pressure',
                'pulse_rate',
                'temperature',
                'respiratory_rate',
                'spo2',
                'weight',
                'height',
                'vitals_recorded_at',
                'vitals_recorded_by',
            ]);
        });
    }
};
