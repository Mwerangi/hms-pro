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
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('ward_number')->unique(); // e.g., W-001, ICU-01
            $table->string('ward_name'); // e.g., General Ward A, ICU, NICU
            $table->enum('ward_type', ['general', 'semi-private', 'private', 'icu', 'nicu', 'picu', 'emergency'])->default('general');
            $table->string('floor')->nullable(); // Floor number or name
            $table->string('building')->nullable(); // Building name/number
            $table->integer('total_beds')->default(0);
            $table->integer('available_beds')->default(0);
            $table->integer('occupied_beds')->default(0);
            $table->text('description')->nullable();
            $table->string('nurse_in_charge')->nullable(); // Name of nurse
            $table->string('contact_number')->nullable();
            $table->decimal('base_charge_per_day', 10, 2)->default(0); // Daily ward charge
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
