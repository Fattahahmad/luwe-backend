<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;

class ExploreTestRecipes extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        // Resep dengan kata pertama "Nasi" untuk testing grouping
        $nasiRecipes = [
            [
                'title' => 'Nasi Goreng Ayam',
                'description' => 'Nasi goreng dengan potongan ayam yang lezat',
                'cooking_time' => 25,
                'category' => 'main_course'
            ],
            [
                'title' => 'Nasi Goreng Seafood',
                'description' => 'Nasi goreng dengan seafood segar',
                'cooking_time' => 30,
                'category' => 'main_course'
            ],
            [
                'title' => 'Nasi Uduk Betawi',
                'description' => 'Nasi uduk khas Jakarta dengan lauk lengkap',
                'cooking_time' => 40,
                'category' => 'main_course'
            ]
        ];

        // Resep dengan kata pertama "Ayam" untuk testing grouping  
        $ayamRecipes = [
            [
                'title' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan bumbu madu yang manis',
                'cooking_time' => 35,
                'category' => 'main_course'
            ],
            [
                'title' => 'Ayam Geprek Sambal',
                'description' => 'Ayam geprek dengan sambal pedas',
                'cooking_time' => 20,
                'category' => 'main_course'
            ]
        ];

        // Resep dengan kata pertama "Soto" untuk testing grouping
        $sotoRecipes = [
            [
                'title' => 'Soto Ayam Lamongan',
                'description' => 'Soto ayam khas Lamongan yang gurih',
                'cooking_time' => 45,
                'category' => 'appetizer'
            ],
            [
                'title' => 'Soto Betawi',
                'description' => 'Soto betawi dengan santan yang creamy',
                'cooking_time' => 50,
                'category' => 'main_course'
            ]
        ];

        $allRecipes = array_merge($nasiRecipes, $ayamRecipes, $sotoRecipes);

        foreach ($allRecipes as $recipeData) {
            Recipe::create([
                'user_id' => $user->id,
                'title' => $recipeData['title'],
                'description' => $recipeData['description'],
                'cooking_time' => $recipeData['cooking_time'],
                'category' => $recipeData['category']
            ]);
            
            $this->command->info("✓ Created: {$recipeData['title']}");
        }

        $this->command->info("Successfully created " . count($allRecipes) . " test recipes for explore grouping!");
    }
}
