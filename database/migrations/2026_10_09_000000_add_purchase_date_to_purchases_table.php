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
        if (!Schema::hasColumn('purchases', 'purchase_date')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->timestamp('purchase_date')->nullable()->after('driver_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('purchases', 'purchase_date')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropColumn('purchase_date');
            });
        }
    }
};
