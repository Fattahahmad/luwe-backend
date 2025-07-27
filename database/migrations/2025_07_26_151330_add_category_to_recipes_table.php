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
        Schema::table('recipes', function (Blueprint $table) {
            // Add category column with ENUM for data integrity
            $table->enum('category', ['appetizer', 'main_course', 'dessert'])
                  ->default('main_course')
                  ->after('cooking_time');
            
            // Add indexes for performance
            $table->index('category');
            $table->index(['category', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['recipes_category_index']);
            $table->dropIndex(['recipes_category_created_at_index']);
            
            // Drop column
            $table->dropColumn('category');
        });
    }
};
