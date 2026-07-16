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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('dealer_id')->unique();
            $table->string('dealer_code', 20)->unique();
            $table->foreignId('branch_id')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->string('name');
            $table->string('business_name');
            $table->string('contact_number');
            $table->text('address');
            $table->tinyInteger('status')->default(1);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
