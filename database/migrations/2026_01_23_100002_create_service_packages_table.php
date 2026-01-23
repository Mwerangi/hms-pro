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
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_code')->unique();
            $table->string('package_name');
            $table->text('description')->nullable();
            $table->json('services'); // Array of service_id and quantities
            $table->decimal('total_standard_price', 10, 2);
            $table->decimal('package_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('validity_days')->default(30);
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
        Schema::dropIfExists('service_packages');
    }
};
