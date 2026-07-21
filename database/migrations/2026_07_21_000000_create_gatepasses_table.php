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
        Schema::create('gatepasses', function (Blueprint $table) {
            $table->id();
            $table->string('gatepass_number')->unique();
            $table->string('gatepass_type')->default('outward'); // outward, inward
            $table->string('movement_type')->default('sale'); // sale, purchase, transfer, other
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->foreignId('dealer_id')->nullable()->constrained('dealers', 'id')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'id')->onDelete('cascade');
            $table->foreignId('transporter_id')->nullable()->constrained('transporters', 'transporter_id')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles', 'vehicle_id')->onDelete('cascade');
            $table->string('driver_name')->nullable();
            $table->string('driver_number')->nullable();
            $table->timestamp('gatepass_date');
            $table->json('gatepass_images')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending'); // pending, approved, dispatched, completed, cancelled
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gatepasses');
    }
};
