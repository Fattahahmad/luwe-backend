<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Alat;
use App\Models\Step;
use App\Models\RecipeBahan;
use App\Models\RecipeAlat;
use Illuminate\Database\Seeder;

class ExploreTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Get some existing bahans and alats
        $bahans = Bahan::take(5)->get();
        $alats = Alat::take(3)->get();

        // Dummy recipes with similar first words for grouping
        $recipesData = [
            // Nasi group
            [
                'title' => 'Nasi Goreng Ayam Spesial',
                'description' => 'Nasi goreng ayam dengan bumbu istimewa yang gurih dan lezat',
                'cooking_time' => 25,
                'category' => 'main_course'
            ],
            [
                'title' => 'Nasi Goreng Seafood',
                'description' => 'Nasi goreng dengan campuran seafood segar',
                'cooking_time' => 30,
                'category' => 'main_course'
            ],
            [
                'title' => 'Nasi Gudeg Jogja',
                'description' => 'Nasi gudeg khas Yogyakarta dengan cita rasa manis',
                'cooking_time' => 45,
                'category' => 'main_course'
            ],

            // Ayam group
            [
                'title' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan marinasi madu yang manis',
                'cooking_time' => 35,
                'category' => 'main_course'
            ],
            [
                'title' => 'Ayam Geprek Sambal Matah',
                'description' => 'Ayam geprek crispy dengan sambal matah segar',
                'cooking_time' => 20,
                'category' => 'main_course'
            ],
            [
                'title' => 'Ayam Katsu Crispy',
                'description' => 'Ayam katsu renyah dengan saus katsu',
                'cooking_time' => 25,
                'category' => 'main_course'
            ],

            // Soto group
            [
                'title' => 'Soto Ayam Lamongan',
                'description' => 'Soto ayam khas Lamongan dengan kuah bening segar',
                'cooking_time' => 40,
                'category' => 'appetizer'
            ],
            [
                'title' => 'Soto Betawi Santan',
                'description' => 'Soto Betawi dengan kuah santan yang kental dan gurih',
                'cooking_time' => 50,
                'category' => 'main_course'
            ],

            // Rendang group
            [
                'title' => 'Rendang Daging Sapi',
                'description' => 'Rendang daging sapi asli Padang dengan bumbu lengkap',
                'cooking_time' => 120,
                'category' => 'main_course'
            ],
            [
                'title' => 'Rendang Ayam Pedas',
                'description' => 'Rendang ayam dengan level kepedasan tinggi',
                'cooking_time' => 90,
                'category' => 'main_course'
            ],

            // Gado group
            [
                'title' => 'Gado Gado Jakarta',
                'description' => 'Gado-gado dengan sayuran segar dan bumbu kacang',
                'cooking_time' => 15,
                'category' => 'appetizer'
            ],
            [
                'title' => 'Gado Gado Surabaya',
                'description' => 'Gado-gado khas Surabaya dengan lontong',
                'cooking_time' => 20,
                'category' => 'appetizer'
            ],

            // Bakso group
            [
                'title' => 'Bakso Malang Original',
                'description' => 'Bakso Malang dengan berbagai isian',
                'cooking_time' => 30,
                'category' => 'main_course'
            ],
            [
                'title' => 'Bakso Urat Pedas',
                'description' => 'Bakso urat dengan kuah pedas dan sayuran',
                'cooking_time' => 35,
                'category' => 'main_course'
            ],

            // Dessert groups
            [
                'title' => 'Kue Lapis Legit',
                'description' => 'Kue lapis legit dengan rasa original',
                'cooking_time' => 60,
                'category' => 'dessert'
            ],
            [
                'title' => 'Kue Nastar Nanas',
                'description' => 'Kue nastar dengan selai nanas homemade',
                'cooking_time' => 45,
                'category' => 'dessert'
            ],

            // Es group
            [
                'title' => 'Es Cendol Durian',
                'description' => 'Es cendol dengan topping durian segar',
                'cooking_time' => 10,
                'category' => 'dessert'
            ],
            [
                'title' => 'Es Campur Jakarta',
                'description' => 'Es campur dengan berbagai topping',
                'cooking_time' => 15,
                'category' => 'dessert'
            ],
        ];

        foreach ($recipesData as $recipeData) {
            // Create recipe
            $recipe = Recipe::create([
                'user_id' => $user->id,
                'title' => $recipeData['title'],
                'description' => $recipeData['description'],
                'cooking_time' => $recipeData['cooking_time'],
                'category' => $recipeData['category'],
            ]);

            // Add some steps
            $steps = [
                'Siapkan semua bahan yang diperlukan',
                'Panaskan minyak di wajan dengan api sedang',
                'Masukkan bumbu dasar dan tumis hingga harum',
                'Tambahkan bahan utama dan masak sesuai resep',
                'Koreksi rasa dan sajikan selagi hangat'
            ];

            foreach ($steps as $index => $stepText) {
                Step::create([
                    'recipe_id' => $recipe->id,
                    'step_number' => $index + 1,
                    'instruction' => $stepText
                ]);
            }

            // Add some bahans if available
            if ($bahans->count() > 0) {
                foreach ($bahans->take(3) as $bahan) {
                    RecipeBahan::create([
                        'recipe_id' => $recipe->id,
                        'bahan_id' => $bahan->id,
                        'amount' => rand(100, 500),
                        'unit' => 'gram'
                    ]);
                }
            }

            // Add some alats if available
            if ($alats->count() > 0) {
                foreach ($alats->take(2) as $alat) {
                    RecipeAlat::create([
                        'recipe_id' => $recipe->id,
                        'alat_id' => $alat->id,
                        'amount' => '1 buah'
                    ]);
                }
            }
        }

        $this->command->info('Created ' . count($recipesData) . ' test recipes for explore grouping!');
        $this->command->info('Groups that should be created:');
        $this->command->info('- Nasi: 3 recipes');
        $this->command->info('- Ayam: 3 recipes'); 
        $this->command->info('- Soto: 2 recipes');
        $this->command->info('- Rendang: 2 recipes');
        $this->command->info('- Gado: 2 recipes');
        $this->command->info('- Bakso: 2 recipes');
        $this->command->info('- Kue: 2 recipes');
        $this->command->info('- Es: 2 recipes');
    }
}
