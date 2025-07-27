<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/api-tester', function () {
    return view('api-tester');
})->name('api-tester');

Route::get('/recipe-tester', function () {
    return view('recipe-tester');
})->name('recipe-tester');

Route::get('/moora-tester', function () {
    return view('moora-tester');
})->name('moora-tester');

// Test URL generation
Route::get('/test-urls', function () {
    $user = \App\Models\User::first();
    $recipe = \App\Models\Recipe::with('images')->first();

    return response()->json([
        'current_url' => request()->getSchemeAndHttpHost(),
        'user_profile_url' => $user ? $user->profile_picture_url : 'No user found',
        'recipe_thumbnail_url' => $recipe ? $recipe->thumbnail_url : 'No recipe found',
        'recipe_images' => $recipe && $recipe->images ? $recipe->images->map(fn($img) => $img->image_url) : []
    ]);
});

// Test recipe-tester connectivity 
Route::get('/test-recipe-tester', function () {
    return response()->json([
        'status' => 'Recipe Tester API Working',
        'timestamp' => now(),
        'server_url' => request()->getSchemeAndHttpHost(),
        'form_data_support' => 'YES - Use FormData, not JSON',
        'auth_endpoints' => [
            'register' => request()->getSchemeAndHttpHost() . '/api/register',
            'login' => request()->getSchemeAndHttpHost() . '/api/login',
            'logout' => request()->getSchemeAndHttpHost() . '/api/logout'
        ],
        'newest_feature' => [
            'endpoint' => request()->getSchemeAndHttpHost() . '/api/recipes/newest',
            'description' => 'Returns newest recipes sorted by created_at DESC (newest first)',
            'usage' => 'Perfect for Flutter app "Newest" tab'
        ],
        'tip' => 'Use FormData() instead of JSON.stringify() for Laravel API'
    ]);
});

// Test newest endpoint specifically
Route::get('/test-newest', function () {
    $newest = \App\Models\Recipe::with(['user:id,name', 'images'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    return response()->json([
        'status' => 'Newest endpoint test',
        'count' => $newest->count(),
        'newest_recipes' => $newest->map(function($recipe) {
            return [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'created_at' => $recipe->created_at,
                'created_human' => $recipe->created_at->diffForHumans(),
                'user' => $recipe->user->name ?? 'Unknown'
            ];
        }),
        'sorting' => 'DESC (newest first)',
        'api_endpoint' => '/api/recipes/newest'
    ]);
});// Debug ngrok connectivity
Route::get('/ngrok-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Ngrok connection working',
        'host' => request()->getHost(),
        'url' => request()->url(),
        'full_url' => request()->fullUrl(),
        'scheme' => request()->getScheme(),
        'port' => request()->getPort(),
        'headers' => [
            'host' => request()->header('host'),
            'user-agent' => request()->header('user-agent'),
            'x-forwarded-for' => request()->header('x-forwarded-for'),
            'x-forwarded-proto' => request()->header('x-forwarded-proto'),
        ],
        'timestamp' => now()
    ]);
});

// Test image route
Route::get('/test-image', function () {
    return response()->json([
        'storage_path' => storage_path('app/public/images/recipes'),
        'asset_url' => asset('storage/images/recipes/default-recipe.jpg'),
        'file_exists' => file_exists(storage_path('app/public/images/recipes/default-recipe.jpg')),
        'public_storage_exists' => file_exists(public_path('storage')),
    ]);
});

// Simple debug route for notifications
Route::get('/debug-notifications', function () {
    return response()->json([
        'message' => 'Notifications debug endpoint working',
        'timestamp' => now(),
        'user_authenticated' => auth()->check(),
        'user_id' => auth()->id()
    ]);
});

// Create sample notifications for testing
Route::get('/debug-cors', function () {
    return response()->json([
        'message' => 'CORS Test Successful',
        'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
        'client_ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'timestamp' => now(),
        'headers' => request()->headers->all()
    ], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, Accept',
    ]);
});

Route::get('/debug-profile-picture', function () {
    $users = App\Models\User::select('id', 'name', 'profile_picture')->get();

    $result = [];
    foreach ($users as $user) {
        $result[] = [
            'id' => $user->id,
            'name' => $user->name,
            'profile_picture_raw' => $user->profile_picture,
            'profile_picture_url' => $user->profile_picture_url,
        ];
    }

    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/create-sample-notifications', function () {
    try {
        // First create the notifications table if it doesn't exist
        DB::statement("CREATE TABLE IF NOT EXISTS `notifications` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) UNSIGNED NOT NULL,
            `from_user_id` bigint(20) UNSIGNED NOT NULL,
            `recipe_id` bigint(20) UNSIGNED NULL,
            `type` varchar(255) NOT NULL,
            `title` varchar(255) NOT NULL,
            `message` text NOT NULL,
            `is_read` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `notifications_user_id_is_read_index` (`user_id`, `is_read`),
            KEY `notifications_created_at_index` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Check if user exists
        $user = App\Models\User::find(13);
        if (!$user) {
            return response()->json(['error' => 'User with ID 13 not found. Available users: ' . App\Models\User::pluck('id', 'name')->toJson()]);
        }

        // Check if recipe exists
        $recipe = App\Models\Recipe::first();
        if (!$recipe) {
            return response()->json(['error' => 'No recipes found']);
        }

        // Create sample users if they don't exist
        $sampleUsers = ['SiRizky', 'NIGHTSIDE', 'CRHOEX', 'Rpn_03', 'raihansukablabla'];
        $createdCount = 0;

        foreach ($sampleUsers as $index => $userName) {
            $fromUser = App\Models\User::where('name', $userName)->first();
            if (!$fromUser) {
                $fromUser = App\Models\User::create([
                    'name' => $userName,
                    'email' => strtolower($userName) . '@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ]);
            }

            // Check if notification already exists
            $exists = App\Models\Notification::where('user_id', 13)
                ->where('from_user_id', $fromUser->id)
                ->where('recipe_id', $recipe->id)
                ->exists();

            if (!$exists) {
                // Create notification
                App\Models\Notification::create([
                    'user_id' => 13,
                    'from_user_id' => $fromUser->id,
                    'recipe_id' => $recipe->id,
                    'type' => 'recipe_favorited',
                    'title' => 'Resep Difavoritkan',
                    'message' => $userName . ' menambahkan resep anda ke favorit',
                    'is_read' => false
                ]);
                $createdCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sample notifications setup completed',
            'created_count' => $createdCount,
            'total_notifications' => App\Models\Notification::where('user_id', 13)->count(),
            'user_id' => 13,
            'recipe_id' => $recipe->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
