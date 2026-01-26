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
            $table->string('employee_id')->nullable()->after('email');
            $table->string('department')->nullable()->after('employee_id');
            $table->string('specialization')->nullable()->after('department');
            $table->string('license_number')->nullable()->after('specialization');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('license_number');
            $table->date('date_of_joining')->nullable()->after('gender');
            $table->timestamp('last_login_at')->nullable()->after('date_of_joining');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'department',
                'specialization',
                'license_number',
                'gender',
                'date_of_joining',
                'last_login_at',
            ]);
        });
    }
};
