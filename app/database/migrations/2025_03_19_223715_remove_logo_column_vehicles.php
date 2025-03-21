<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verifica se la colonna 'logo' esiste già
        if (!Schema::hasColumn('vehicles', 'logo')) {
            // Se la colonna non esiste, la aggiungi
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('logo')->nullable()->after('model_id'); // Aggiungi la colonna logo
            });
        } else {
            // Ignora la migrazione se la colonna logo esiste già
            // Non facciamo nulla
            return;
        }
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Se la colonna logo esiste, la rimuoviamo
            if (Schema::hasColumn('vehicles', 'logo')) {
                $table->dropColumn('logo');
            }
        });
    }
};
