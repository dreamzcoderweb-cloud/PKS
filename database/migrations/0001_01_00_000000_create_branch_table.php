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
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->string('branch_name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('branch_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_prices');
        Schema::dropIfExists('branches');
    }
};
