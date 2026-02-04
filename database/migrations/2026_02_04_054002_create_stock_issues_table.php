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
        Schema::create('stock_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique();
            $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
            $table->string('issued_to');
            $table->enum('issue_type', ['department', 'patient', 'internal'])->default('internal');
            $table->foreignId('issued_by')->constrained('users')->onDelete('restrict');
            $table->date('issue_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issues');
    }
};
