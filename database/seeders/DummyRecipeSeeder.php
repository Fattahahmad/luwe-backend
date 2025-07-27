<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Bahan;

class DummyRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy recipes data from GPT-generated JSON
        $recipes = array (
          0 => 
          array (
            'title' => 'Tumis Kangkung Sederhana',
            'description' => 'Kangkung segar ditumis dengan bawang putih dan cabai, cocok sebagai lauk pendamping.',
            'cooking_time' => 15,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Kangkung',
                'amount' => 1,
                'unit' => 'ikat',
              ),
              1 => 
              array (
                'name' => 'Bawang putih',
                'amount' => 3,
                'unit' => 'siung',
              ),
              2 => 
              array (
                'name' => 'Cabai merah',
                'amount' => 2,
                'unit' => 'buah',
              ),
              3 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 2,
                'unit' => 'sendok makan',
              ),
              4 => 
              array (
                'name' => 'Garam',
                'amount' => 0.5,
                'unit' => 'sendok teh',
              ),
            ),
          ),
          1 => 
          array (
            'title' => 'Perkedel Kentang',
            'description' => 'Perkedel kentang lembut dan gurih, cocok untuk camilan atau pelengkap nasi.',
            'cooking_time' => 25,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Kentang',
                'amount' => 500,
                'unit' => 'gram',
              ),
              1 => 
              array (
                'name' => 'Telur',
                'amount' => 1,
                'unit' => 'butir',
              ),
              2 => 
              array (
                'name' => 'Bawang merah',
                'amount' => 3,
                'unit' => 'siung',
              ),
              3 => 
              array (
                'name' => 'Garam',
                'amount' => 0.5,
                'unit' => 'sendok teh',
              ),
              4 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 300,
                'unit' => 'mililiter',
              ),
            ),
          ),
          2 => 
          array (
            'title' => 'Tahu Isi Pedas',
            'description' => 'Tahu goreng isi sayuran pedas, renyah di luar dan lembut di dalam.',
            'cooking_time' => 30,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Tahu',
                'amount' => 10,
                'unit' => 'buah',
              ),
              1 => 
              array (
                'name' => 'Wortel',
                'amount' => 1,
                'unit' => 'buah',
              ),
              2 => 
              array (
                'name' => 'Cabai merah',
                'amount' => 3,
                'unit' => 'buah',
              ),
              3 => 
              array (
                'name' => 'Bawang putih',
                'amount' => 2,
                'unit' => 'siung',
              ),
              4 => 
              array (
                'name' => 'Tepung terigu',
                'amount' => 100,
                'unit' => 'gram',
              ),
              5 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 300,
                'unit' => 'mililiter',
              ),
            ),
          ),
          3 => 
          array (
            'title' => 'Telur Dadar Bumbu Iris',
            'description' => 'Telur dadar dengan irisan bawang dan cabai, menu praktis penuh rasa.',
            'cooking_time' => 10,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Telur',
                'amount' => 2,
                'unit' => 'butir',
              ),
              1 => 
              array (
                'name' => 'Bawang merah',
                'amount' => 2,
                'unit' => 'siung',
              ),
              2 => 
              array (
                'name' => 'Cabai hijau',
                'amount' => 2,
                'unit' => 'buah',
              ),
              3 => 
              array (
                'name' => 'Garam',
                'amount' => 0.5,
                'unit' => 'sendok teh',
              ),
              4 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 2,
                'unit' => 'sendok makan',
              ),
            ),
          ),
          4 => 
          array (
            'title' => 'Bakwan Sayur',
            'description' => 'Gorengan sayur renyah berisi wortel dan kol, cocok untuk semua suasana.',
            'cooking_time' => 20,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Wortel',
                'amount' => 1,
                'unit' => 'buah',
              ),
              1 => 
              array (
                'name' => 'Tepung terigu',
                'amount' => 150,
                'unit' => 'gram',
              ),
              2 => 
              array (
                'name' => 'Bawang putih',
                'amount' => 2,
                'unit' => 'siung',
              ),
              3 => 
              array (
                'name' => 'Garam',
                'amount' => 1,
                'unit' => 'sendok teh',
              ),
              4 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 300,
                'unit' => 'mililiter',
              ),
            ),
          ),
          5 => 
          array (
            'title' => 'Tempe Mendoan',
            'description' => 'Tempe goreng tipis berbalut tepung, nikmat disantap dengan sambal kecap.',
            'cooking_time' => 15,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Tempe',
                'amount' => 200,
                'unit' => 'gram',
              ),
              1 => 
              array (
                'name' => 'Tepung terigu',
                'amount' => 100,
                'unit' => 'gram',
              ),
              2 => 
              array (
                'name' => 'Daun bawang',
                'amount' => 1,
                'unit' => 'batang',
              ),
              3 => 
              array (
                'name' => 'Minyak goreng',
                'amount' => 300,
                'unit' => 'mililiter',
              ),
            ),
          ),
          6 => 
          array (
            'title' => 'Sayur Bening Bayam',
            'description' => 'Sayur kuah bening yang menyegarkan, kaya akan zat besi dan rendah kalori.',
            'cooking_time' => 20,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Bayam',
                'amount' => 1,
                'unit' => 'ikat',
              ),
              1 => 
              array (
                'name' => 'Bawang merah',
                'amount' => 3,
                'unit' => 'siung',
              ),
              2 => 
              array (
                'name' => 'Garam',
                'amount' => 1,
                'unit' => 'sendok teh',
              ),
              3 => 
              array (
                'name' => 'Air',
                'amount' => 750,
                'unit' => 'mililiter',
              ),
            ),
          ),
          7 => 
          array (
            'title' => 'Acar Timun',
            'description' => 'Acar segar dari timun, wortel, dan cabai sebagai pelengkap makanan berminyak.',
            'cooking_time' => 10,
            'category' => 'appetizer',
            'ingredients' => 
            array (
              0 => 
              array (
                'name' => 'Timun',
                'amount' => 2,
                'unit' => 'buah',
              ),
              1 => 
              array (
                'name' => 'Wortel',
                'amount' => 1,
                'unit' => 'buah',
              ),
              2 => 
              array (
                'name' => 'Cabai merah',
                'amount' => 2,
                'unit' => 'buah',
              ),
              3 => 
              array (
                'name' => 'Cuka',
                'amount' => 3,
                'unit' => 'sendok makan',
              ),
              4 => 
              array (
                'name' => 'Gula pasir',
                'amount' => 1,
                'unit' => 'sendok makan',
              ),
            ),
          ),
        );

        // Process the recipes
                // Process the recipes
        // Get first user for recipe assignment
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        // Create mapping of bahan names to IDs
        $bahanMapping = Bahan::pluck('id', 'name')->toArray();
        
        $imported = 0;
        $skipped = 0;

        foreach($recipes as $recipeData) {
            // Create recipe
            $recipe = Recipe::create([
                'user_id' => $user->id,
                'title' => $recipeData['title'],
                'description' => $recipeData['description'], 
                'cooking_time' => $recipeData['cooking_time'],
                'category' => $recipeData['category']
            ]);
            
            // Add ingredients
            $ingredientsAdded = 0;
            foreach($recipeData['ingredients'] as $ingredient) {
                $bahanId = $bahanMapping[$ingredient['name']] ?? null;
                if ($bahanId) {
                    $recipe->bahans()->attach($bahanId, [
                        'amount' => $ingredient['amount'],
                        'unit' => $ingredient['unit']
                    ]);
                    $ingredientsAdded++;
                } else {
                    $this->command->warn("Bahan '{$ingredient['name']}' not found for recipe '{$recipe->title}'");
                }
            }
            
            if ($ingredientsAdded > 0) {
                $imported++;
                $this->command->info("✓ Created: {$recipe->title} ({$ingredientsAdded} ingredients)");
            } else {
                $recipe->delete();
                $skipped++;
                $this->command->warn("✗ Skipped: {$recipeData['title']} (no valid ingredients)");
            }
        }

        $this->command->info("Successfully imported {$imported} recipes, skipped {$skipped}");
        
        // Show summary
        $this->command->info("
Summary by category:");
        $summary = Recipe::selectRaw('category, COUNT(*) as count')->groupBy('category')->get();
        foreach($summary as $item) {
            $this->command->info("- {$item->category}: {$item->count} recipes");
        }
    }
}
