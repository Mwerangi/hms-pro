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
            $table->boolean('is_billed')->default(false)->after('status');
            $table->foreignId('bill_id')->nullable()->constrained()->onDelete('set null')->after('is_billed');
            $table->boolean('is_locked')->default(false)->after('bill_id');
            $table->foreignId('locked_by')->nullable()->constrained('users')->onDelete('set null')->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('locked_by');
            $table->foreignId('reopened_by')->nullable()->constrained('users')->onDelete('set null')->after('locked_at');
            $table->timestamp('reopened_at')->nullable()->after('reopened_by');
            $table->text('reopen_reason')->nullable()->after('reopened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['bill_id']);
            $table->dropForeign(['locked_by']);
            $table->dropForeign(['reopened_by']);
            $table->dropColumn([
                'is_billed',
                'bill_id',
                'is_locked',
                'locked_by',
                'locked_at',
                'reopened_by',
                'reopened_at',
                'reopen_reason',
            ]);
        });
    }
};
