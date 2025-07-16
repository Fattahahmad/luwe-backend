<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRecipeController extends Controller
{
    /**
     * Get current user's recipes (for profile page)
     */
    public function myRecipes(Request $request)
    {
        $query = Recipe::where('user_id', Auth::id())
            ->with(['images', 'favorites']);

        // Search functionality
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $recipes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Add favorite status and count
        foreach ($recipes as $recipe) {
            $recipe->is_favorited = $recipe->isFavoritedBy(Auth::id());
            $recipe->favorites_count = $recipe->favorites->count();
        }

        return response()->json([
            'success' => true,
            'message' => 'User recipes retrieved successfully',
            'data' => $recipes
        ]);
    }

    /**
     * Get any user's public recipes
     */
    public function userRecipes(Request $request, $userId)
    {
        $query = Recipe::where('user_id', $userId)
            ->with(['user:id,name', 'images', 'favorites']);

        // Search functionality
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $recipes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Add favorite status for authenticated user
        if (Auth::check()) {
            $authUserId = Auth::id();
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = $recipe->isFavoritedBy($authUserId);
                $recipe->favorites_count = $recipe->favorites->count();
            }
        } else {
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = false;
                $recipe->favorites_count = $recipe->favorites->count();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User recipes retrieved successfully',
            'data' => $recipes
        ]);
    }

    /**
     * Get user's recipe statistics
     */
    public function recipeStats()
    {
        $userId = Auth::id();
        
        $totalRecipes = Recipe::where('user_id', $userId)->count();
        $totalFavorites = Recipe::where('user_id', $userId)
            ->withCount('favorites')
            ->get()
            ->sum('favorites_count');

        return response()->json([
            'success' => true,
            'data' => [
                'total_recipes' => $totalRecipes,
                'total_favorites_received' => $totalFavorites
            ]
        ]);
    }
}
