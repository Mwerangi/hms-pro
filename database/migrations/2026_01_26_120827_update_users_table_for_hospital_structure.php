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
        Schema::table('users', function (Blueprint $table) {
            // Change department from string to foreign key
            $table->dropColumn('department');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('employee_id')->constrained('departments')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->after('department_id')->constrained('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['department_id', 'branch_id']);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('employee_id');
        });
    }
};
