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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicleId')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('customerId')->constrained('customers')->onDelete('cascade');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->foreignId('deliveryLocationId')->constrained('location');
            $table->foreignId('pickupLocationId')->constrained('location');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
