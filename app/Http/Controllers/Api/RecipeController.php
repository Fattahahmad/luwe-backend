<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeImage;
use App\Models\RecipeStep;
use App\Models\Alat;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes (public - no auth needed)
     */
    public function index(Request $request)
    {
        $query = Recipe::with(['user:id,name', 'images', 'favorites']);

        // Search functionality
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $recipes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Add favorite status for authenticated user
        if (Auth::check()) {
            $userId = Auth::id();
            foreach ($recipes as $recipe) {
                $recipe->is_favorited = $recipe->isFavoritedBy($userId);
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
            'message' => 'Recipes retrieved successfully',
            'data' => $recipes
        ]);
    }

    /**
     * Store a newly created recipe (auth required)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cooking_time' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'steps' => 'required|array|min:1',
            'steps.*.instruction' => 'required|string',
            'alats' => 'nullable|array',
            'alats.*.id' => 'required_with:alats|exists:alats,id',
            'alats.*.amount' => 'required_with:alats|string',
            'bahans' => 'nullable|array',
            'bahans.*.id' => 'required_with:bahans|exists:bahans,id',
            'bahans.*.amount' => 'required_with:bahans|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create recipe
            $recipe = Recipe::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'cooking_time' => $request->cooking_time,
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '_thumb_' . $recipe->id . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('images/recipes'), $thumbnailName);
                $recipe->update(['thumbnail' => $thumbnailName]);
            }

            // Handle additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imageName = time() . '_img_' . $recipe->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/recipes'), $imageName);
                    
                    RecipeImage::create([
                        'recipe_id' => $recipe->id,
                        'image_path' => $imageName,
                        'order' => $index + 1
                    ]);
                }
            }

            // Handle steps
            foreach ($request->steps as $index => $step) {
                RecipeStep::create([
                    'recipe_id' => $recipe->id,
                    'step_number' => $index + 1,
                    'instruction' => $step['instruction']
                ]);
            }

            // Handle alats
            if ($request->has('alats')) {
                $alatData = [];
                foreach ($request->alats as $alat) {
                    $alatData[$alat['id']] = ['amount' => $alat['amount']];
                }
                $recipe->alats()->attach($alatData);
            }

            // Handle bahans
            if ($request->has('bahans')) {
                $bahanData = [];
                foreach ($request->bahans as $bahan) {
                    $bahanData[$bahan['id']] = ['amount' => $bahan['amount']];
                }
                $recipe->bahans()->attach($bahanData);
            }

            DB::commit();

            // Load relationships for response
            $recipe->load(['user:id,name', 'images', 'steps', 'alats', 'bahans']);

            return response()->json([
                'success' => true,
                'message' => 'Recipe created successfully',
                'data' => $recipe
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified recipe (public - no auth needed)
     */
    public function show($id)
    {
        $recipe = Recipe::with([
            'user:id,name',
            'images',
            'steps',
            'alats',
            'bahans',
            'favorites'
        ])->find($id);

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not found'
            ], 404);
        }

        // Add favorite status for authenticated user
        if (Auth::check()) {
            $recipe->is_favorited = $recipe->isFavoritedBy(Auth::id());
        } else {
            $recipe->is_favorited = false;
        }

        $recipe->favorites_count = $recipe->favorites->count();

        return response()->json([
            'success' => true,
            'message' => 'Recipe retrieved successfully',
            'data' => $recipe
        ]);
    }

    /**
     * Update the specified recipe (auth required - only owner)
     */
    public function update(Request $request, $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not found'
            ], 404);
        }

        // Check if user owns this recipe
        if ($recipe->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only update your own recipes'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'cooking_time' => 'sometimes|required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'steps' => 'sometimes|required|array|min:1',
            'steps.*.instruction' => 'required_with:steps|string',
            'alats' => 'nullable|array',
            'alats.*.id' => 'required_with:alats|exists:alats,id',
            'alats.*.amount' => 'required_with:alats|string',
            'bahans' => 'nullable|array',
            'bahans.*.id' => 'required_with:bahans|exists:bahans,id',
            'bahans.*.amount' => 'required_with:bahans|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Debug log
            \Log::info('Update request data:', [
                'has_thumbnail' => $request->hasFile('thumbnail'),
                'has_images' => $request->hasFile('images'),
                'images_count' => $request->hasFile('images') ? count($request->file('images')) : 0
            ]);

            // Update basic fields
            $recipe->update($request->only(['title', 'description', 'cooking_time']));

            // Handle thumbnail update
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($recipe->thumbnail) {
                    $oldThumbnail = public_path('images/recipes/' . $recipe->thumbnail);
                    if (file_exists($oldThumbnail)) {
                        unlink($oldThumbnail);
                    }
                }

                $thumbnail = $request->file('thumbnail');
                $thumbnailName = time() . '_thumb_' . $recipe->id . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('images/recipes'), $thumbnailName);
                $recipe->update(['thumbnail' => $thumbnailName]);
            }

            // Handle additional images update
            if ($request->hasFile('images')) {
                // Delete old images
                $oldImages = $recipe->images;
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('images/recipes/' . $oldImage->image_path);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Delete from database
                $recipe->images()->delete();

                // Upload new images
                foreach ($request->file('images') as $index => $image) {
                    $imageName = time() . '_img_' . $recipe->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/recipes'), $imageName);
                    
                    RecipeImage::create([
                        'recipe_id' => $recipe->id,
                        'image_path' => $imageName,
                        'order' => $index + 1
                    ]);
                }
            }

            // Handle steps update
            if ($request->has('steps')) {
                // Delete old steps
                $recipe->steps()->delete();
                
                // Create new steps
                foreach ($request->steps as $index => $step) {
                    RecipeStep::create([
                        'recipe_id' => $recipe->id,
                        'step_number' => $index + 1,
                        'instruction' => $step['instruction']
                    ]);
                }
            }

            // Handle alats update
            if ($request->has('alats')) {
                $recipe->alats()->detach();
                $alatData = [];
                foreach ($request->alats as $alat) {
                    $alatData[$alat['id']] = ['amount' => $alat['amount']];
                }
                $recipe->alats()->attach($alatData);
            }

            // Handle bahans update
            if ($request->has('bahans')) {
                $recipe->bahans()->detach();
                $bahanData = [];
                foreach ($request->bahans as $bahan) {
                    $bahanData[$bahan['id']] = ['amount' => $bahan['amount']];
                }
                $recipe->bahans()->attach($bahanData);
            }

            DB::commit();

            // Load relationships for response
            $recipe->load(['user:id,name', 'images', 'steps', 'alats', 'bahans']);

            return response()->json([
                'success' => true,
                'message' => 'Recipe updated successfully',
                'data' => $recipe
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified recipe (auth required - only owner)
     */
    public function destroy($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not found'
            ], 404);
        }

        // Check if user owns this recipe
        if ($recipe->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own recipes'
            ], 403);
        }

        try {
            // Delete thumbnail
            if ($recipe->thumbnail) {
                $thumbnailPath = public_path('images/recipes/' . $recipe->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            // Delete recipe images
            foreach ($recipe->images as $image) {
                $imagePath = public_path('images/recipes/' . $image->image_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete recipe (cascade will handle related records)
            $recipe->delete();

            return response()->json([
                'success' => true,
                'message' => 'Recipe deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
