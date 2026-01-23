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
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->text('result_values')->nullable()->after('results'); // Structured test result values (JSON)
            $table->string('result_file_path')->nullable()->after('result_values'); // Uploaded report file
            $table->string('imaging_file_path')->nullable()->after('result_file_path'); // For radiology images
            $table->text('radiologist_findings')->nullable()->after('imaging_file_path'); // Radiology interpretation
            $table->string('priority')->default('normal')->after('urgency'); // normal, high, critical
            $table->timestamp('scheduled_at')->nullable()->after('order_date'); // For imaging appointments
            $table->timestamp('completed_at')->nullable()->after('reported_at'); // When test completed
            $table->unsignedBigInteger('processed_by')->nullable()->after('collected_by'); // Lab tech who processed
            
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn([
                'result_values',
                'result_file_path',
                'imaging_file_path',
                'radiologist_findings',
                'priority',
                'scheduled_at',
                'completed_at',
                'processed_by'
            ]);
        });
    }
};
