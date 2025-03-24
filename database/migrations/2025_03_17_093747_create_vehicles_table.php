<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('make_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->year('year');
            $table->integer('seats');
            $table->string('transmission');
            $table->string('color');
            $table->string('plateNumber');
            $table->string('bodyType');
            $table->string('location');
            $table->decimal('dailyPrice', 10, 2);
            $table->enum('status', ['available', 'maintenance', 'unavailable']);
            $table->integer('odometer');
            $table->string('fuel_type');
            $table->integer('km_per_day');
            $table->decimal('extra_km_price', 10, 2);
            $table->decimal('basic_daily_price', 10, 2);
            $table->decimal('franchise', 10, 2);
            $table->string('imageUrl');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
