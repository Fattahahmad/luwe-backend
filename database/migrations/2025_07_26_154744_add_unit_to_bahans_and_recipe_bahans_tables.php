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
        // Add unit JSON field to bahans table (master data)
        Schema::table('bahans', function (Blueprint $table) {
            $table->json('units')->after('name')->nullable();
        });

        // Add unit string field to recipe_bahans table (user selection)
        Schema::table('recipe_bahans', function (Blueprint $table) {
            $table->string('unit')->after('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bahans', function (Blueprint $table) {
            $table->dropColumn('units');
        });

        Schema::table('recipe_bahans', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
