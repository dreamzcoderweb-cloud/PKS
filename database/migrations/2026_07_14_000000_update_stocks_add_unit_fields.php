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
            $table->foreignId('branch_id')->after('stock_code')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->foreignId('unit_id')->after('branch_id')->constrained('units', 'unit_id')->onDelete('cascade');
            $table->foreignId('alter_unit_id')->after('unit_id')->constrained('alternate_units', 'alter_unit_id')->onDelete('cascade');
            $table->string('unit_value', 15, 2)->after('alter_unit_id');
            $table->string('alter_unit_value', 15, 2)->after('unit_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['alter_unit_id']);
            $table->dropColumn(['branch_id', 'unit_id', 'alter_unit_id', 'unit_value', 'alter_unit_value']);
        });
    }
};
