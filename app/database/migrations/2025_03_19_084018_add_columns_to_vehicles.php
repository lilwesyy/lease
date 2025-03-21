<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verifica se le colonne make_id e model_id esistono già
        if (!Schema::hasColumn('vehicles', 'make_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->unsignedBigInteger('make_id')->nullable()->after('model');
                $table->foreign('make_id')->references('id')->on('vehicle_makes')->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('vehicles', 'model_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->unsignedBigInteger('model_id')->nullable()->after('make_id');
                $table->foreign('model_id')->references('id')->on('vehicle_models')->onDelete('set null');
            });
        }

        // Opzionalmente, rimuovi le vecchie colonne
        Schema::table('vehicles', function (Blueprint $table) {
            // Aggiungi solo se esistono
            if (Schema::hasColumn('vehicles', 'make')) {
                $table->dropColumn('make');
            }
            if (Schema::hasColumn('vehicles', 'model')) {
                $table->dropColumn('model');
            }
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
