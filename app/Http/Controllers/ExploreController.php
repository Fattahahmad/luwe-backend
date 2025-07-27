<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    /**
     * Get recipe groups based on title similarity (first word)
     */
    public function getRecipeGroups(Request $request)
    {
        try {
            // Get pagination parameters
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 12); // Default 12 groups per page
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'count'); // count, name, recent
            $minRecipes = $request->get('min_recipes', 2);

            // Validate parameters
            $page = max(1, intval($page));
            $perPage = max(1, min(50, intval($perPage))); // Max 50 groups per page
            $minRecipes = max(2, intval($minRecipes));

            // Get all recipes with user relationship
            $recipes = Recipe::with('user')
                ->get();

            // Group recipes by first word of title
            $groups = $this->groupByFirstWord($recipes);

            // Filter groups with minimum recipes
            $filteredGroups = $groups->filter(function ($group) use ($minRecipes) {
                return count($group['recipes']) >= $minRecipes;
            });

            // Search filter
            if (!empty($search)) {
                $filteredGroups = $filteredGroups->filter(function ($group) use ($search) {
                    return stripos($group['keyword'], $search) !== false || 
                           stripos($group['display_name'], $search) !== false;
                });
            }

            // Sort groups
            $sortedGroups = $this->sortGroups($filteredGroups, $sortBy);

            // Pagination
            $totalGroups = $sortedGroups->count();
            $totalPages = ceil($totalGroups / $perPage);
            $offset = ($page - 1) * $perPage;
            $paginatedGroups = $sortedGroups->slice($offset, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'groups' => $paginatedGroups,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total_groups' => $totalGroups,
                        'total_pages' => $totalPages,
                        'has_next' => $page < $totalPages,
                        'has_prev' => $page > 1
                    ],
                    'filters' => [
                        'search' => $search,
                        'sort_by' => $sortBy,
                        'min_recipes' => $minRecipes
                    ],
                    'total_recipes' => $recipes->count()
                ],
                'message' => 'Recipe groups retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recipe groups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sort groups based on criteria
     */
    private function sortGroups($groups, $sortBy)
    {
        switch ($sortBy) {
            case 'name':
                return $groups->sortBy('keyword')->values();
            
            case 'recent':
                return $groups->sortByDesc(function ($group) {
                    // Sort by most recent recipe in group
                    $latestDate = collect($group['recipes'])->max('created_at');
                    return $latestDate;
                })->values();
            
            case 'count':
            default:
                return $groups->sortByDesc('count')->values();
        }
    }

    /**
     * Group recipes by first word of title
     */
    private function groupByFirstWord($recipes)
    {
        $groups = [];

        foreach ($recipes as $recipe) {
            $firstWord = $this->getFirstWord($recipe->title);
            
            // Skip if first word is too short or common
            if (strlen($firstWord) < 2 || in_array($firstWord, ['dan', 'atau', 'the', 'a'])) {
                continue;
            }

            // Find existing group or create new one
            if (isset($groups[$firstWord])) {
                // Add recipe to existing group
                $groups[$firstWord]['recipes'][] = $this->formatRecipeForGroup($recipe);
                $groups[$firstWord]['count'] = count($groups[$firstWord]['recipes']);
                
                // Update category distribution
                $this->updateCategoryDistribution($groups[$firstWord], $recipe->category);
            } else {
                // Create new group
                $groups[$firstWord] = [
                    'keyword' => $firstWord,
                    'display_name' => ucfirst($firstWord),
                    'count' => 1,
                    'recipes' => [$this->formatRecipeForGroup($recipe)],
                    'category_distribution' => [
                        'appetizer' => $recipe->category === 'appetizer' ? 1 : 0,
                        'main_course' => $recipe->category === 'main_course' ? 1 : 0,
                        'dessert' => $recipe->category === 'dessert' ? 1 : 0,
                    ],
                    'primary_category' => $recipe->category
                ];
            }
        }

        return collect(array_values($groups));
    }

    /**
     * Extract first word from title
     */
    private function getFirstWord($title)
    {
        // Clean title and get first word
        $cleaned = trim(strtolower($title));
        $words = explode(' ', $cleaned);
        return $words[0];
    }

    /**
     * Format recipe data for group display
     */
    private function formatRecipeForGroup($recipe)
    {
        return [
            'id' => $recipe->id,
            'title' => $recipe->title,
            'description' => $recipe->description,
            'thumbnail_url' => $recipe->thumbnail_url,
            'cooking_time' => $recipe->cooking_time,
            'category' => $recipe->category,
            'favorites_count' => $recipe->favorites_count ?? 0,
            'user' => [
                'id' => $recipe->user->id,
                'name' => $recipe->user->name
            ],
            'created_at' => $recipe->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Update category distribution for group
     */
    private function updateCategoryDistribution(&$group, $category)
    {
        if (isset($group['category_distribution'][$category])) {
            $group['category_distribution'][$category]++;
        }

        // Update primary category (most common category in group)
        $maxCount = 0;
        $primaryCategory = $group['primary_category'];
        
        foreach ($group['category_distribution'] as $cat => $count) {
            if ($count > $maxCount) {
                $maxCount = $count;
                $primaryCategory = $cat;
            }
        }
        
        $group['primary_category'] = $primaryCategory;
    }

    /**
     * Get recipes in specific group
     */
    public function getGroupRecipes(Request $request, $keyword)
    {
        try {
            $recipes = Recipe::with('user')
                ->get()
                ->filter(function ($recipe) use ($keyword) {
                    return $this->getFirstWord($recipe->title) === strtolower($keyword);
                });

            $formattedRecipes = $recipes->map(function ($recipe) {
                return $this->formatRecipeForGroup($recipe);
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'keyword' => $keyword,
                    'display_name' => ucfirst($keyword),
                    'recipes' => $formattedRecipes,
                    'total_count' => $formattedRecipes->count()
                ],
                'message' => "Recipes for keyword '{$keyword}' retrieved successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving group recipes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get explore data by category filter
     */
    public function getExploreByCategory(Request $request, $category)
    {
        try {
            // Get pagination parameters (same as main method)
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 12);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'count');
            $minRecipes = $request->get('min_recipes', 2);

            // Validate parameters
            $page = max(1, intval($page));
            $perPage = max(1, min(50, intval($perPage)));
            $minRecipes = max(2, intval($minRecipes));

            $recipes = Recipe::with('user')
                ->where('category', $category)
                ->get();

            $groups = $this->groupByFirstWord($recipes);
            
            // Filter groups with minimum recipes
            $filteredGroups = $groups->filter(function ($group) use ($minRecipes) {
                return count($group['recipes']) >= $minRecipes;
            });

            // Search filter
            if (!empty($search)) {
                $filteredGroups = $filteredGroups->filter(function ($group) use ($search) {
                    return stripos($group['keyword'], $search) !== false || 
                           stripos($group['display_name'], $search) !== false;
                });
            }

            // Sort groups
            $sortedGroups = $this->sortGroups($filteredGroups, $sortBy);

            // Pagination
            $totalGroups = $sortedGroups->count();
            $totalPages = ceil($totalGroups / $perPage);
            $offset = ($page - 1) * $perPage;
            $paginatedGroups = $sortedGroups->slice($offset, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category,
                    'groups' => $paginatedGroups,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total_groups' => $totalGroups,
                        'total_pages' => $totalPages,
                        'has_next' => $page < $totalPages,
                        'has_prev' => $page > 1
                    ],
                    'filters' => [
                        'search' => $search,
                        'sort_by' => $sortBy,
                        'min_recipes' => $minRecipes
                    ],
                    'total_recipes' => $recipes->count()
                ],
                'message' => "Explore data for category '{$category}' retrieved successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving category explore data: ' . $e->getMessage()
            ], 500);
        }
    }
}
