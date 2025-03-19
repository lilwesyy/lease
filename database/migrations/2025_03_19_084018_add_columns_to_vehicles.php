<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Aggiungi le nuove colonne
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('make_id')->nullable()->after('model');
            $table->unsignedBigInteger('model_id')->nullable()->after('make_id');

            $table->foreign('make_id')->references('id')->on('vehicle_makes')->onDelete('set null');
            $table->foreign('model_id')->references('id')->on('vehicle_models')->onDelete('set null');
        });

        // Qui potresti aggiungere codice per migrare i dati dalle colonne stringa alle relazioni
        // Questo dovrà essere fatto manualmente o con un seeder

        // Opzionalmente, rimuovi le vecchie colonne
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['make', 'model']);
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['make_id']);
            $table->dropForeign(['model_id']);
            $table->dropColumn(['make_id', 'model_id']);
        });
    }
};
