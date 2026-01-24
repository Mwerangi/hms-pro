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
        Schema::table('patient_charges', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['service_id']);
            
            // Modify column to be nullable
            $table->foreignId('service_id')->nullable()->change()->constrained()->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_charges', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['service_id']);
            
            // Make it non-nullable again
            $table->foreignId('service_id')->change()->constrained()->onDelete('restrict');
        });
    }
};
