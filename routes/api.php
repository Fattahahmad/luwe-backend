<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserRecipeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Image service route
Route::get('/image/{filename}', [ImageController::class, 'show']);

// Handle OPTIONS preflight requests
Route::options('/{any}', function() {
    return response()->json('OK', 200);
})->where('any', '.*');

// Public routes (tidak perlu authentication)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Debug route
Route::get('/debug', function() {
    return response()->json([
        'status' => 'API working',
        'timestamp' => now(),
        'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
        'client_ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'cors_enabled' => true,
        'laravel_version' => app()->version(),
        'notifications_table_exists' => Schema::hasTable('notifications'),
        'request_method' => request()->method(),
        'request_headers' => request()->headers->all()
    ]);
});

// Recipe routes (public untuk view, auth untuk CUD)
Route::get('/recipes', [RecipeController::class, 'index']); // Public - semua bisa lihat
Route::get('/recipes/{id}', [RecipeController::class, 'show']); // Public - semua bisa lihat detail
Route::get('/users/{userId}/recipes', [UserRecipeController::class, 'userRecipes']); // Get user's public recipes

// Master data routes (public)
Route::get('/alats', [MasterDataController::class, 'getAlats']);
Route::get('/bahans', [MasterDataController::class, 'getBahans']);
Route::get('/alats/search', [MasterDataController::class, 'searchAlats']);
Route::get('/bahans/search', [MasterDataController::class, 'searchBahans']);

// Protected routes (perlu authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    
    // Recipe routes (authenticated)
    Route::post('/recipes', [RecipeController::class, 'store']); // Create recipe
    Route::put('/recipes/{id}', [RecipeController::class, 'update']); // Update recipe (only owner)
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']); // Delete recipe (only owner)
    
    // Favorite routes
    Route::get('/favorites', [FavoriteController::class, 'index']); // Get user's favorites
    Route::post('/recipes/{id}/favorite', [FavoriteController::class, 'store']); // Add to favorites
    Route::delete('/recipes/{id}/favorite', [FavoriteController::class, 'destroy']); // Remove from favorites
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index']); // Get notifications
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); // Mark as read
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']); // Mark all as read
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']); // Get unread count
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']); // Delete notification
    
    // User recipe routes
    Route::get('/my-recipes', [UserRecipeController::class, 'myRecipes']); // Get current user's recipes
    Route::get('/my-recipes/stats', [UserRecipeController::class, 'recipeStats']); // Get user's recipe stats
});
