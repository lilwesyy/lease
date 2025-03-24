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
        // Check if the 'logo' column exists before attempting to drop it
        if (Schema::hasTable('vehicles') && Schema::hasColumn('vehicles', 'logo')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the 'logo' column doesn't exist before attempting to add it back
        if (Schema::hasTable('vehicles') && !Schema::hasColumn('vehicles', 'logo')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('logo')->nullable();
            });
        }
    }
};
