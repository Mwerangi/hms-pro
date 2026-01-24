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
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->decimal('price_per_unit', 10, 2)->default(0)->after('instructions');
            $table->decimal('total_price', 10, 2)->default(0)->after('price_per_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropColumn(['price_per_unit', 'total_price']);
        });
    }
};
