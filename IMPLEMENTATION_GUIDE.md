# Implementation Guide - MOORA Recipe Recommendation System

## 1. Project Structure

```
luwe/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── RecommendationController.php
│   ├── Models/
│   │   ├── Recipe.php
│   │   ├── Bahan.php
│   │   └── User.php
│   └── Services/
│       └── MooraRecommendationService.php
├── database/
│   ├── migrations/
│   │   ├── create_recipes_table.php
│   │   ├── create_bahans_table.php
│   │   └── create_recipe_bahan_table.php
│   └── seeders/
│       ├── BahanSeeder.php
│       └── DummyRecipeSeeder.php
├── routes/
│   └── api.php
└── config/
    └── app.php
```

## 2. Database Design

### 2.1 Entity Relationship Diagram

```
[Users] 1 ----< [Recipes] >---- N [Bahans]
   |              |                  |
   id             id                 id
   name           title              name
   email          description        
                  cooking_time       
                  category           
                  user_id            
```

### 2.2 Table Structures

#### recipes
```sql
CREATE TABLE recipes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    cooking_time INT NOT NULL,
    category ENUM('appetizer', 'main_course', 'dessert') NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### bahans
```sql
CREATE TABLE bahans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    units JSON NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

#### recipe_bahan (Pivot Table)
```sql
CREATE TABLE recipe_bahan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    bahan_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(8,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (bahan_id) REFERENCES bahans(id) ON DELETE CASCADE
);
```

## 3. Model Implementation

### 3.1 Recipe Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = [
        'user_id',
        'title', 
        'description',
        'cooking_time',
        'category'
    ];

    protected $casts = [
        'cooking_time' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bahans(): BelongsToMany  
    {
        return $this->belongsToMany(Bahan::class, 'recipe_bahan')
                    ->withPivot(['amount', 'unit'])
                    ->withTimestamps();
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope untuk filter berdasarkan waktu maksimal
    public function scopeMaxCookingTime($query, $maxTime)
    {
        return $query->where('cooking_time', '<=', $maxTime);
    }
}
```

### 3.2 Bahan Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bahan extends Model
{
    protected $fillable = ['name', 'units'];

    protected $casts = [
        'units' => 'array'
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_bahan')
                    ->withPivot(['amount', 'unit'])
                    ->withTimestamps();
    }

    // Accessor untuk mendapatkan unit dalam format string
    public function getUnitsStringAttribute()
    {
        return $this->units ? implode(', ', $this->units) : '';
    }
}
```

## 4. Service Layer Implementation

### 4.1 MooraRecommendationService.php - Core Logic

```php
<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Collection;

class MooraRecommendationService
{
    private $ingredientWeight = 0.7;  // 70% weight untuk ingredient match
    private $timeWeight = 0.3;        // 30% weight untuk time efficiency  
    private $threshold = 0.3;         // Minimum score untuk recommendation
    private $maxResults = 10;         // Maximum jumlah hasil

    public function getRecommendations(array $availableIngredientIds): array
    {
        // Step 1: Pre-filtering - hanya resep yang punya minimal 1 bahan tersedia
        $eligibleRecipes = $this->getEligibleRecipes($availableIngredientIds);
        
        if ($eligibleRecipes->isEmpty()) {
            return $this->formatResponse([], 0, 0);
        }

        // Step 2: Hitung MOORA score untuk setiap resep
        $scoredRecipes = $eligibleRecipes->map(function ($recipe) use ($availableIngredientIds) {
            $scoreData = $this->calculateMooraScore($recipe, $availableIngredientIds);
            
            return [
                'recipe' => $recipe,
                'moora_score' => $scoreData['score'],
                'ingredient_ratio' => $scoreData['ingredient_ratio'],
                'time_efficiency' => $scoreData['time_efficiency'],
                'available_ingredients' => $scoreData['available_ingredients'],
                'total_ingredients' => $scoreData['total_ingredients']
            ];
        });

        // Step 3: Sort berdasarkan MOORA score (descending)
        $rankedRecipes = $scoredRecipes->sortByDesc('moora_score')->values();

        // Step 4: Filter berdasarkan threshold
        $filteredResults = $rankedRecipes->filter(function ($result) {
            return $result['moora_score'] >= $this->threshold;
        });

        // Step 5: Limit hasil maksimal
        $finalResults = $filteredResults->take($this->maxResults);

        return $this->formatResponse(
            $finalResults, 
            $eligibleRecipes->count(), 
            $filteredResults->count()
        );
    }

    private function getEligibleRecipes(array $availableIngredientIds): Collection
    {
        return Recipe::whereHas('bahans', function ($query) use ($availableIngredientIds) {
            $query->whereIn('bahan_id', $availableIngredientIds);
        })->with(['bahans', 'user'])->get();
    }

    private function calculateMooraScore($recipe, array $availableIngredientIds): array
    {
        // 1. Hitung Ingredient Match Ratio
        $totalIngredients = $recipe->bahans->count();
        $availableCount = $recipe->bahans->whereIn('id', $availableIngredientIds)->count();
        $ingredientRatio = $totalIngredients > 0 ? $availableCount / $totalIngredients : 0;

        // 2. Hitung Time Efficiency (asumsi max cooking time = 120 menit)
        $maxCookingTime = 120;
        $timeEfficiency = ($maxCookingTime - $recipe->cooking_time) / $maxCookingTime;
        $timeEfficiency = max(0, min(1, $timeEfficiency)); // Clamp antara 0-1

        // 3. MOORA Normalization (Vector Normalization)
        $vectorSum = sqrt(($ingredientRatio * $ingredientRatio) + ($timeEfficiency * $timeEfficiency));
        
        if ($vectorSum == 0) {
            $ingredientNorm = 0;
            $timeNorm = 0;
        } else {
            $ingredientNorm = $ingredientRatio / $vectorSum;
            $timeNorm = $timeEfficiency / $vectorSum;
        }

        // 4. Weighted Sum (MOORA Optimization)
        $mooraScore = ($this->ingredientWeight * $ingredientNorm) + ($this->timeWeight * $timeNorm);

        return [
            'score' => $mooraScore,
            'ingredient_ratio' => $ingredientRatio,
            'time_efficiency' => $timeEfficiency,
            'available_ingredients' => $availableCount,
            'total_ingredients' => $totalIngredients
        ];
    }

    private function formatResponse($results, int $totalEligible, int $totalRecommended): array
    {
        $formattedResults = $results->map(function ($result) {
            return [
                'id' => $result['recipe']->id,
                'title' => $result['recipe']->title,
                'description' => $result['recipe']->description,
                'cooking_time' => $result['recipe']->cooking_time,
                'category' => $result['recipe']->category,
                'moora_score' => round($result['moora_score'], 4),
                'ingredient_match_percentage' => round($result['ingredient_ratio'] * 100, 1),
                'time_efficiency_percentage' => round($result['time_efficiency'] * 100, 1),
                'available_ingredients_count' => $result['available_ingredients'],
                'total_ingredients_count' => $result['total_ingredients'],
                'user' => [
                    'id' => $result['recipe']->user->id,
                    'name' => $result['recipe']->user->name
                ]
            ];
        })->toArray();

        return [
            'success' => true,
            'data' => $formattedResults,
            'metadata' => [
                'total_eligible_recipes' => $totalEligible,
                'total_recommended' => $totalRecommended,
                'threshold_used' => $this->threshold,
                'weights' => [
                    'ingredient_weight' => $this->ingredientWeight,
                    'time_weight' => $this->timeWeight
                ]
            ]
        ];
    }

    // Getter methods untuk testing/debugging
    public function getThreshold(): float
    {
        return $this->threshold;
    }

    public function getWeights(): array
    {
        return [
            'ingredient' => $this->ingredientWeight,
            'time' => $this->timeWeight
        ];
    }
}
```

## 5. Controller Implementation

### 5.1 RecommendationController.php

```php
<?php

namespace App\Http\Controllers;

use App\Services\MooraRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RecommendationController extends Controller
{
    private MooraRecommendationService $mooraService;

    public function __construct(MooraRecommendationService $mooraService)
    {
        $this->mooraService = $mooraService;
    }

    public function mooraRecommendations(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'ingredient_ids' => 'required|array|min:1',
                'ingredient_ids.*' => 'integer|exists:bahans,id'
            ]);

            // Dapatkan rekomendasi menggunakan MOORA
            $recommendations = $this->mooraService->getRecommendations($validated['ingredient_ids']);

            return response()->json($recommendations);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }
}
```

## 6. Route Configuration

### 6.1 API Routes (routes/api.php)

```php
<?php

use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::prefix('recipes')->group(function () {
    Route::post('/moora-recommendations', [RecommendationController::class, 'mooraRecommendations']);
});

// Optional: Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Add protected routes here if needed
});
```

## 7. Database Seeding Strategy

### 7.1 BahanSeeder.php - Ingredient Seeding

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bahan;

class BahanSeeder extends Seeder
{
    public function run(): void
    {
        $bahans = [
            ['name' => 'Beras', 'units' => ['kg', 'gram', 'cup']],
            ['name' => 'Bawang merah', 'units' => ['siung', 'gram', 'buah']],
            ['name' => 'Bawang putih', 'units' => ['siung', 'gram', 'buah']],
            ['name' => 'Cabai merah', 'units' => ['buah', 'gram']],
            ['name' => 'Cabai hijau', 'units' => ['buah', 'gram']],
            // ... 40 more ingredients
        ];

        foreach ($bahans as $bahan) {
            Bahan::firstOrCreate(
                ['name' => $bahan['name']], 
                ['units' => $bahan['units']]
            );
        }
    }
}
```

### 7.2 DummyRecipeSeeder.php - Recipe Seeding

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Bahan;

class DummyRecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        // Create mapping of bahan names to IDs
        $bahanMapping = Bahan::pluck('id', 'name')->toArray();
        
        $recipes = [
            [
                'title' => 'Tumis Kangkung Sederhana',
                'description' => 'Kangkung segar ditumis dengan bawang putih dan cabai, cocok sebagai lauk pendamping.',
                'cooking_time' => 15,
                'category' => 'appetizer',
                'ingredients' => [
                    ['name' => 'Kangkung', 'amount' => 1, 'unit' => 'ikat'],
                    ['name' => 'Bawang putih', 'amount' => 3, 'unit' => 'siung'],
                    ['name' => 'Cabai merah', 'amount' => 2, 'unit' => 'buah'],
                    ['name' => 'Minyak goreng', 'amount' => 2, 'unit' => 'sendok makan'],
                    ['name' => 'Garam', 'amount' => 0.5, 'unit' => 'sendok teh']
                ]
            ],
            // ... 7 more recipes
        ];

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
    }
}
```

## 8. Configuration and Setup

### 8.1 Environment Setup

```bash
# 1. Install Laravel dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate

# 4. Seed database
php artisan db:seed --class=BahanSeeder
php artisan db:seed --class=DummyRecipeSeeder

# 5. Start development server
php artisan serve
```

### 8.2 Testing the Implementation

```bash
# Test MOORA endpoint with curl
curl -X POST http://localhost:8000/api/recipes/moora-recommendations \
  -H "Content-Type: application/json" \
  -d '{"ingredient_ids": [1, 2, 3, 4, 5]}'
```

## 9. Performance Optimization

### 9.1 Database Optimization

```sql
-- Add indexes for better query performance
CREATE INDEX idx_recipe_bahan_recipe_id ON recipe_bahan(recipe_id);
CREATE INDEX idx_recipe_bahan_bahan_id ON recipe_bahan(bahan_id);
CREATE INDEX idx_recipes_category ON recipes(category);
CREATE INDEX idx_recipes_cooking_time ON recipes(cooking_time);
```

### 9.2 Caching Strategy

```php
// In MooraRecommendationService.php
use Illuminate\Support\Facades\Cache;

public function getRecommendations(array $availableIngredientIds): array
{
    $cacheKey = 'moora_recommendations_' . md5(json_encode(sort($availableIngredientIds)));
    
    return Cache::remember($cacheKey, 3600, function () use ($availableIngredientIds) {
        // Existing logic here
        return $this->calculateRecommendations($availableIngredientIds);
    });
}
```

## 10. Error Handling and Logging

### 10.1 Comprehensive Error Handling

```php
// In RecommendationController.php
use Illuminate\Support\Facades\Log;

public function mooraRecommendations(Request $request): JsonResponse
{
    try {
        $validated = $request->validate([
            'ingredient_ids' => 'required|array|min:1',
            'ingredient_ids.*' => 'integer|exists:bahans,id'
        ]);

        $recommendations = $this->mooraService->getRecommendations($validated['ingredient_ids']);
        
        // Log successful requests for analytics
        Log::info('MOORA recommendation generated', [
            'ingredient_count' => count($validated['ingredient_ids']),
            'result_count' => count($recommendations['data'])
        ]);

        return response()->json($recommendations);

    } catch (ValidationException $e) {
        Log::warning('MOORA validation failed', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        Log::error('MOORA recommendation error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Internal server error'
        ], 500);
    }
}
```

---

**Author**: GitHub Copilot  
**Last Updated**: July 27, 2025  
**Laravel Version**: 11.x  
**PHP Version**: 8.x+
