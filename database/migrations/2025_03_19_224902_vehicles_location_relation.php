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
        Schema::table('vehicles', function (Blueprint $table) {
            // First check if the location column exists and drop it
            if (Schema::hasColumn('vehicles', 'location')) {
                $table->dropColumn('location');
            }
            
            // Add the new foreign key column
            $table->unsignedBigInteger('location_id')->nullable()->after('year');
            
            // Create the foreign key constraint
            $table->foreign('location_id')
                  ->references('id')
                  ->on('location')
                  ->onDelete('set null'); // When a location is deleted, set the location_id to null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['location_id']);
            
            // Drop the column
            $table->dropColumn('location_id');
            
            // Optionally restore the original location column if needed
            $table->string('location')->nullable()->after('year');
        });
    }
};