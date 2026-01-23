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
        Schema::table('wards', function (Blueprint $table) {
            // Drop the old nurse_in_charge column
            $table->dropColumn('nurse_in_charge');
        });

        Schema::table('wards', function (Blueprint $table) {
            // Add nurse_id as foreign key to users table
            $table->foreignId('nurse_id')->nullable()->after('description')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->dropForeign(['nurse_id']);
            $table->dropColumn('nurse_id');
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->string('nurse_in_charge')->nullable();
        });
    }
};
