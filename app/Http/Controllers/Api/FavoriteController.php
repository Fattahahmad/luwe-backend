<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeFavorite;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Get user's favorite recipes
     */
    public function index()
    {
        $favoriteRecipes = Auth::user()->favoriteRecipes()
            ->with(['user:id,name', 'images'])
            ->orderBy('recipe_favorites.created_at', 'desc')
            ->paginate(10);

        // Add favorite status and count
        foreach ($favoriteRecipes as $recipe) {
            $recipe->is_favorited = true; // Always true since these are favorites
            $recipe->favorites_count = $recipe->favorites->count();
        }

        return response()->json([
            'success' => true,
            'message' => 'Favorite recipes retrieved successfully',
            'data' => $favoriteRecipes
        ]);
    }

    /**
     * Add recipe to favorites
     */
    public function store(Request $request, $recipeId)
    {
        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not found'
            ], 404);
        }

        // Check if already favorited
        $existingFavorite = RecipeFavorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe already in favorites',
                'data' => [
                    'is_favorited' => true,
                    'action' => 'already_favorited'
                ]
            ], 409);
        }

        // Add to favorites
        RecipeFavorite::create([
            'user_id' => Auth::id(),
            'recipe_id' => $recipeId
        ]);

        // Send notification to recipe owner (if not self-favoriting)
        if ($recipe->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $recipe->user_id,
                'from_user_id' => Auth::id(),
                'recipe_id' => $recipeId,
                'type' => 'recipe_favorited',
                'title' => Auth::user()->name . ' menambahkan resep anda ke favorit',
                'message' => Auth::user()->name . ' menambahkan resep "' . $recipe->title . '" ke favorit'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Recipe added to favorites'
        ], 201);
    }

    /**
     * Remove recipe from favorites
     */
    public function destroy($recipeId)
    {
        $favorite = RecipeFavorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not in favorites'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recipe removed from favorites'
        ]);
    }
}
