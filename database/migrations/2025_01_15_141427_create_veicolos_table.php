<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('veicoli', function (Blueprint $table) {
            $table->id();
            $table->string('modello');
            $table->text('descrizione');
            $table->decimal('prezzo_giornaliero', 8, 2);
            $table->string('immagine')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('veicoli');
    }
};
