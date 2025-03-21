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
        Schema::create('damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('damagePosition');
            $table->string('damagePositionLabel');
            $table->string('damageSeverity');
            $table->string('damageSeverityLabel');
            $table->string('damageType');
            $table->string('damageTypeLabel');
            $table->text('damageDescription');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('damages');
    }
};
