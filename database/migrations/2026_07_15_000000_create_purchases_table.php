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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id')->unique();
            $table->foreignId('branch_id')->constrained('branches', 'branch_id')->onDelete('cascade');
            $table->foreignId('dealer_id')->constrained('dealers', 'id')->onDelete('cascade');
            $table->string('lot_number');
            $table->foreignId('transporter_id')->constrained('transporters', 'transporter_id')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles', 'vehicle_id')->onDelete('cascade');
            $table->string('driver_number');
            $table->json('purchase_images');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
