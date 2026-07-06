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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique();
            $table->string('customer_code', 20)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile_number')->unique();
            $table->string('password');
            $table->foreignId('branch_id')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->string('business')->nullable();
            $table->text('address')->nullable();
            $table->string('location')->nullable();
            $table->string('gst_number')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
