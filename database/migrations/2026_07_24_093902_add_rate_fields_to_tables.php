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
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('rate', 15, 2)->nullable()->after('alter_unit_value');
            $table->decimal('rate_stock', 15, 2)->nullable()->after('rate');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('rate', 15, 2)->nullable()->after('alter_unit_type');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->decimal('rate', 15, 2)->nullable()->after('alternate_unit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['rate', 'rate_stock']);
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('rate');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
    }
};
