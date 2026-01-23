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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique();
            $table->string('service_name');
            $table->enum('category', [
                'consultation',
                'laboratory',
                'radiology',
                'procedure',
                'pharmacy',
                'room_charge',
                'nursing_care',
                'emergency',
                'surgery',
                'other'
            ]);
            $table->string('department')->nullable();
            $table->text('description')->nullable();
            $table->decimal('standard_charge', 10, 2)->default(0);
            $table->boolean('taxable')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(0);
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
        Schema::dropIfExists('services');
    }
};
