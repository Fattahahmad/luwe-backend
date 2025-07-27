<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Recipe;
use App\Http\Controllers\ExploreController;

echo "=== DEBUG EXPLORE FEATURE ===\n\n";

// Get some test recipes
$recipes = Recipe::all();
echo "Total recipes in database: " . $recipes->count() . "\n\n";

// Test recipes with 'Nasi' prefix
$nasiRecipes = $recipes->filter(function($recipe) {
    return stripos($recipe->title, 'Nasi') === 0;
});

echo "Recipes starting with 'Nasi': " . $nasiRecipes->count() . "\n";
foreach($nasiRecipes as $recipe) {
    echo "- " . $recipe->title . "\n";
}
echo "\n";

// Test getFirstWord method
$controller = new ExploreController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getFirstWord');
$method->setAccessible(true);

echo "Testing getFirstWord method:\n";
$testTitles = ['Nasi Goreng', 'Ayam Bakar', 'Soto Ayam', 'Telur dadar'];
foreach($testTitles as $title) {
    $firstWord = $method->invoke($controller, $title);
    echo "'{$title}' -> '{$firstWord}' (length: " . strlen($firstWord) . ")\n";
}
echo "\n";

// Test groupByFirstWord method
$groupMethod = $reflection->getMethod('groupByFirstWord');
$groupMethod->setAccessible(true);

echo "Testing groupByFirstWord with ALL recipes:\n";
$groups = $groupMethod->invoke($controller, $recipes);

echo "All groups found: " . $groups->count() . "\n";
foreach($groups as $group) {
    echo "- Keyword: '{$group['keyword']}', Count: {$group['count']}\n";
}

echo "\nGroups with 2+ recipes:\n";
$filteredGroups = $groups->filter(function ($group) {
    return $group['count'] >= 2;
});
echo "Filtered groups: " . $filteredGroups->count() . "\n";
foreach($filteredGroups as $group) {
    echo "- Keyword: '{$group['keyword']}', Count: {$group['count']}\n";
    foreach($group['recipes'] as $recipe) {
        echo "  * {$recipe['title']}\n";
    }
}
