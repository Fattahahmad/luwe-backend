<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe API Tester - Luwe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
        }

        .response-area {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
        }

        .step-input {
            margin-bottom: 10px;
        }

        .ingredient-row {
            margin-bottom: 10px;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            object-fit: cover;
        }

        .image-error {
            width: 100px;
            height: 100px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">🍽️ Recipe API Tester - Luwe</h1>

        <!-- Auth Status -->
        <div class="alert alert-info" id="authStatus">
            <strong>Status:</strong> <span id="authStatusText">Not logged in</span>
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="logoutBtn"
                style="display: none;">Logout</button>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="auth-tab" data-bs-toggle="tab" data-bs-target="#auth"
                    type="button">Authentication</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recipes-tab" data-bs-toggle="tab" data-bs-target="#recipes"
                    type="button">All Recipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="newest-tab" data-bs-toggle="tab" data-bs-target="#newest"
                    type="button">🔥 Newest</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="appetizers-tab" data-bs-toggle="tab" data-bs-target="#appetizers"
                    type="button">🥗 Appetizers</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="main-courses-tab" data-bs-toggle="tab" data-bs-target="#main-courses"
                    type="button">🍽️ Main Courses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="desserts-tab" data-bs-toggle="tab" data-bs-target="#desserts"
                    type="button">🍰 Desserts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="explore-tab" data-bs-toggle="tab" data-bs-target="#explore"
                    type="button">🔍 Explore</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="create-recipe-tab" data-bs-toggle="tab" data-bs-target="#create-recipe"
                    type="button">Create Recipe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="update-recipe-tab" data-bs-toggle="tab" data-bs-target="#update-recipe"
                    type="button" style="display: none;">Update Recipe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites"
                    type="button">Favorites</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications"
                    type="button">Notifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="my-recipes-tab" data-bs-toggle="tab" data-bs-target="#my-recipes"
                    type="button">My Recipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="master-data-tab" data-bs-toggle="tab" data-bs-target="#master-data"
                    type="button">Master Data</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Authentication Tab -->
            <div class="tab-pane fade show active" id="auth" role="tabpanel">
                <div class="row mt-3">
                    <!-- Login -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Login</h5>
                            </div>
                            <div class="card-body">
                                <form id="loginForm">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="loginEmail" class="form-control" name="email"
                                            value="test@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="loginPassword" class="form-control" name="password"
                                            value="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Register -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Register</h5>
                            </div>
                            <div class="card-body">
                                <form id="registerForm">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" id="registerName" class="form-control" name="name"
                                            value="Test User" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="registerEmail" class="form-control" name="email"
                                            value="newuser@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="registerPassword" class="form-control"
                                            name="password" value="password123" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="registerPasswordConfirm" class="form-control"
                                            name="password_confirmation" value="password123" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Register</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipes Tab -->
            <div class="tab-pane fade" id="recipes" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>All Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadRecipes()">Refresh</button>
                        </div>
                        <div class="card-body">
                            <!-- Search and Per Page Controls -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="searchRecipes"
                                        placeholder="Search recipes...">
                                </div>
                                <div class="col-md-4">
                                    <select id="recipesPerPage" class="form-select" onchange="loadRecipes(1)">
                                        <option value="5">5 per page</option>
                                        <option value="10" selected>10 per page</option>
                                        <option value="20">20 per page</option>
                                        <option value="50">50 per page</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="recipesLoading" style="display: none;" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading recipes...</p>
                            </div>
                            
                            <div id="recipesList"></div>
                            
                            <!-- Pagination -->
                            <div id="recipesPagination" class="d-flex justify-content-center mt-4" style="display: none !important;">
                                <!-- Pagination will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newest Recipes Tab -->
            <div class="tab-pane fade" id="newest" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>🔥 Newest Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadNewestRecipes()">
                                <i class="fas fa-sync-alt"></i> Refresh Newest
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Per Page Control -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Showing newest recipes first (latest uploads on top)
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <select id="newestPerPage" class="form-select" onchange="loadNewestRecipes(1)">
                                        <option value="5">5 per page</option>
                                        <option value="10" selected>10 per page</option>
                                        <option value="20">20 per page</option>
                                        <option value="50">50 per page</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="newestLoading" style="display: none;" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading newest recipes...</p>
                            </div>
                            
                            <div id="newestRecipesList"></div>
                            
                            <!-- Pagination -->
                            <div id="newestPagination" class="d-flex justify-content-center mt-4" style="display: none !important;">
                                <!-- Pagination will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appetizers Tab -->
            <div class="tab-pane fade" id="appetizers" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>🥗 Appetizer Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadAppetizerRecipes()">
                                <i class="fas fa-sync-alt"></i> Refresh Appetizers
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Showing appetizer recipes (starters, finger foods, etc.)
                                </small>
                            </div>
                            <div id="appetizerRecipesList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Courses Tab -->
            <div class="tab-pane fade" id="main-courses" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>🍽️ Main Course Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadMainCourseRecipes()">
                                <i class="fas fa-sync-alt"></i> Refresh Main Courses
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Showing main course recipes (lunch, dinner, hearty meals)
                                </small>
                            </div>
                            <div id="mainCourseRecipesList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desserts Tab -->
            <div class="tab-pane fade" id="desserts" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>🍰 Dessert Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadDessertRecipes()">
                                <i class="fas fa-sync-alt"></i> Refresh Desserts
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Showing dessert recipes (cakes, pastries, sweet treats)
                                </small>
                            </div>
                            <div id="dessertRecipesList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explore Tab -->
            <div class="tab-pane fade" id="explore" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>🔍 Explore Recipes by Similarity</h5>
                            <button type="button" class="btn btn-primary" onclick="loadExploreGroups()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Search and Controls -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Search Groups:</label>
                                    <div class="input-group">
                                        <input type="text" id="exploreSearchInput" class="form-control" 
                                               placeholder="Cari grup (contoh: nasi, ayam...)" 
                                               onkeyup="handleExploreSearch()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearExploreSearch()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sort By:</label>
                                    <select id="exploreSortBy" class="form-select" onchange="loadExploreGroups()">
                                        <option value="count">Most Recipes</option>
                                        <option value="name">Alphabetical</option>
                                        <option value="recent">Most Recent</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Min Recipes:</label>
                                    <select id="exploreMinRecipes" class="form-select" onchange="loadExploreGroups()">
                                        <option value="2">2+ recipes</option>
                                        <option value="3">3+ recipes</option>
                                        <option value="4">4+ recipes</option>
                                        <option value="5">5+ recipes</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Per Page:</label>
                                    <select id="explorePerPage" class="form-select" onchange="loadExploreGroups()">
                                        <option value="6">6 groups</option>
                                        <option value="12" selected>12 groups</option>
                                        <option value="24">24 groups</option>
                                        <option value="48">48 groups</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="mb-3">
                                <label class="form-label">Filter by Category:</label>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary active" onclick="filterExploreByCategory('all')">
                                        Semua
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" onclick="filterExploreByCategory('appetizer')">
                                        🥗 Appetizer
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" onclick="filterExploreByCategory('main_course')">
                                        🍽️ Main Course
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" onclick="filterExploreByCategory('dessert')">
                                        🍰 Dessert
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Info text -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Resep dikelompokkan berdasarkan kesamaan kata pertama pada judul
                                </small>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="exploreLoading" style="display: none;" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading recipe groups...</p>
                            </div>
                            
                            <!-- Explore content -->
                            <div id="exploreContent"></div>
                            
                            <!-- Pagination -->
                            <div id="explorePagination" class="d-flex justify-content-center mt-4" style="display: none !important;">
                                <!-- Pagination will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Recipe Tab -->
            <div class="tab-pane fade" id="create-recipe" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create New Recipe</h5>
                        </div>
                        <div class="card-body">
                            <form id="createRecipeForm" enctype="multipart/form-data">
                                <!-- Basic Info -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Recipe Title</label>
                                            <input type="text" class="form-control" name="title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Cooking Time (minutes)</label>
                                            <input type="number" class="form-control" name="cooking_time"
                                                min="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="appetizer">🥗 Appetizer</option>
                                                <option value="main_course">🍽️ Main Course</option>
                                                <option value="dessert">🍰 Dessert</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>

                                <!-- Images -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Thumbnail Image</label>
                                            <input type="file" class="form-control" name="thumbnail"
                                                accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Additional Images (max 4)</label>
                                            <input type="file" class="form-control" name="images[]"
                                                accept="image/*" multiple>
                                        </div>
                                    </div>
                                </div>

                                <!-- Steps -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Steps</label>
                                    <div id="stepsContainer">
                                        <div class="step-input">
                                            <div class="input-group">
                                                <span class="input-group-text">1</span>
                                                <textarea class="form-control" name="steps[0][instruction]" placeholder="Enter cooking step..." required></textarea>
                                                <button class="btn btn-outline-danger" type="button"
                                                    onclick="removeStep(this)">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addStep()">Add Step</button>
                                </div>

                                <!-- Alats -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Tools (Alat)</label>
                                    <div id="alatsContainer">
                                        <div class="ingredient-row">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-select" name="alats[0][id]">
                                                        <option value="">Select cooking tool...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="alats[0][amount]" placeholder="Amount/description">
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-outline-danger" type="button"
                                                        onclick="removeIngredient(this)">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addAlat()">Add Tool</button>
                                </div>

                                <!-- Bahans -->
                                <div class="mb-3">
                                    <label class="form-label">Ingredients (Bahan)</label>
                                    <div id="bahansContainer">
                                        <div class="ingredient-row">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select class="form-select" name="bahans[0][id]" onchange="loadUnitsForBahan(this, 0)">
                                                        <option value="">Select ingredient...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control"
                                                        name="bahans[0][amount]"
                                                        placeholder="Amount (e.g., 500)">
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-select" name="bahans[0][unit]" id="unitSelect_0">
                                                        <option value="">Select unit...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-outline-danger" type="button"
                                                        onclick="removeIngredient(this)">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addBahan()">Add Ingredient</button>
                                </div>

                                <button type="submit" class="btn btn-success">Create Recipe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Recipe Tab -->
            <div class="tab-pane fade" id="update-recipe" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Update Recipe</h5>
                            <button type="button" class="btn btn-secondary"
                                onclick="cancelUpdateRecipe()">Cancel</button>
                        </div>
                        <div class="card-body">
                            <form id="updateRecipeForm" enctype="multipart/form-data">
                                <input type="hidden" id="updateRecipeId" name="recipe_id">

                                <!-- Basic Info -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Recipe Title</label>
                                            <input type="text" class="form-control" id="updateTitle"
                                                name="title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Cooking Time (minutes)</label>
                                            <input type="number" class="form-control" id="updateCookingTime"
                                                name="cooking_time" min="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control" id="updateCategory" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="appetizer">🥗 Appetizer</option>
                                                <option value="main_course">🍽️ Main Course</option>
                                                <option value="dessert">🍰 Dessert</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="updateDescription" name="description" rows="3"></textarea>
                                </div>

                                <!-- Current Images Display -->
                                <div class="mb-3">
                                    <label class="form-label">Current Images</label>
                                    <div id="currentImagesDisplay" class="mb-2"></div>
                                </div>

                                <!-- New Images -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">New Thumbnail Image (optional)</label>
                                            <input type="file" class="form-control" name="thumbnail"
                                                accept="image/*">
                                            <small class="text-muted">Leave empty to keep current thumbnail</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">New Additional Images (optional, max 4)</label>
                                            <input type="file" class="form-control" name="images[]"
                                                accept="image/*" multiple>
                                            <small class="text-muted">Select new images to replace ALL current
                                                additional images</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Steps -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Steps</label>
                                    <div id="updateStepsContainer">
                                        <!-- Steps will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addUpdateStep()">Add Step</button>
                                </div>

                                <!-- Alats -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Tools (Alat)</label>
                                    <div id="updateAlatsContainer">
                                        <!-- Alats will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addUpdateAlat()">Add Tool</button>
                                </div>

                                <!-- Bahans -->
                                <div class="mb-3">
                                    <label class="form-label">Ingredients (Bahan)</label>
                                    <div id="updateBahansContainer">
                                        <!-- Bahans will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addUpdateBahan()">Add Ingredient</button>
                                </div>

                                <button type="submit" class="btn btn-success">Update Recipe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Favorites Tab -->
            <div class="tab-pane fade" id="favorites" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>My Favorite Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadFavorites()">Refresh</button>
                        </div>
                        <div class="card-body">
                            <div id="favoritesList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Notifications <span class="badge bg-primary" id="unreadCount">0</span></h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="markAllAsRead()">Mark All Read</button>
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="loadNotifications()">Refresh</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="unreadOnlyFilter"
                                    onchange="loadNotifications()">
                                <label class="form-check-label" for="unreadOnlyFilter">
                                    Show unread only
                                </label>
                            </div>
                            <div id="notificationsList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Recipes Tab -->
            <div class="tab-pane fade" id="my-recipes" role="tabpanel">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>My Recipes</h5>
                            <button type="button" class="btn btn-primary" onclick="loadMyRecipes()">Refresh</button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="searchMyRecipes"
                                        placeholder="Search my recipes...">
                                </div>
                                <div class="col-md-6">
                                    <div id="recipeStats" class="text-muted"></div>
                                </div>
                            </div>
                            <div id="myRecipesList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master Data Tab -->
            <div class="tab-pane fade" id="master-data" role="tabpanel">
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Cooking Tools (Alat)</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-3" onclick="loadAlats()">Load
                                        Alats</button>
                                    <div id="alatsList"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Ingredients (Bahan)</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-3" onclick="loadBahans()">Load
                                        Bahans</button>
                                    <div id="bahansList"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Units (Satuan)</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-3" onclick="testUnitsAPI()">Test Units API</button>
                                    <button type="button" class="btn btn-info mb-3" onclick="testBahanUnits()">Test Bahan Units</button>
                                    <div id="unitsList"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Display -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>API Response</h5>
            </div>
            <div class="card-body">
                <pre id="responseDisplay" class="response-area"></pre>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let authToken = localStorage.getItem('auth_token');
        let currentUser = null;
        let stepCounter = 1;
        let alatCounter = 1;
        let bahanCounter = 1;
        let updateStepCounter = 0;
        let updateAlatCounter = 0;
        let updateBahanCounter = 0;
        let alatsData = [];
        let bahansData = [];

        // Pagination variables
        let currentRecipesPage = 1;
        let currentNewestPage = 1;
        let recipesSearchTimeout;

        // Dynamic Base API URL - works for both localhost and ngrok
        const API_BASE = window.location.origin + '/api';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved user data
            const savedUser = localStorage.getItem('current_user');
            if (savedUser) {
                currentUser = JSON.parse(savedUser);
            }

            updateAuthStatus();
            loadMasterData();
            
            // Load units data for frontend
            loadAllUnits();

            // Load unread notification count if logged in
            if (authToken) {
                loadUnreadCount();
            }
        });

        // Update auth status display
        function updateAuthStatus() {
            const authStatusText = document.getElementById('authStatusText');
            const logoutBtn = document.getElementById('logoutBtn');

            if (authToken) {
                authStatusText.textContent = 'Logged in';
                logoutBtn.style.display = 'inline-block';
            } else {
                authStatusText.textContent = 'Not logged in';
                logoutBtn.style.display = 'none';
            }
        }

        // Display response
        function displayResponse(response) {
            document.getElementById('responseDisplay').textContent = JSON.stringify(response, null, 2);
        }

        // Login form handler
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;

            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }

            try {
                console.log('Attempting login...'); // Debug

                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);

                const response = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                        // No Content-Type for FormData - browser sets it automatically
                    },
                    body: formData
                });

                console.log('Login response status:', response.status); // Debug

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Login error:', errorText);
                    throw new Error(`HTTP ${response.status}: Login failed`);
                }

                const data = await response.json();
                console.log('Login data:', data); // Debug

                displayResponse(data);

                if (data.success) {
                    authToken = data.data.token;
                    currentUser = data.data.user;
                    localStorage.setItem('auth_token', authToken);
                    localStorage.setItem('current_user', JSON.stringify(currentUser));
                    updateAuthStatus();
                    alert('Login successful!');

                    // Clear form
                    this.reset();
                } else {
                    alert('Login failed: ' + (data.message || 'Invalid credentials'));
                }
            } catch (error) {
                console.error('Login error:', error);
                displayResponse({
                    error: error.message
                });
                alert('Login error: ' + error.message);
            }
        });

        // Register form handler
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const passwordConfirm = document.getElementById('registerPasswordConfirm').value;

            if (!name || !email || !password || !passwordConfirm) {
                alert('Please fill in all fields');
                return;
            }

            if (password !== passwordConfirm) {
                alert('Passwords do not match');
                return;
            }

            try {
                console.log('Attempting registration...'); // Debug

                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('password', password);
                formData.append('password_confirmation', passwordConfirm);

                const response = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                        // No Content-Type for FormData - browser sets it automatically
                    },
                    body: formData
                });

                console.log('Register response status:', response.status); // Debug

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Register error:', errorText);
                    throw new Error(`HTTP ${response.status}: Registration failed`);
                }

                const data = await response.json();
                console.log('Register data:', data); // Debug

                displayResponse(data);

                if (data.success) {
                    alert('Registration successful! You can now login.');
                    this.reset();
                } else {
                    alert('Registration failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Register error:', error);
                displayResponse({
                    error: error.message
                });
                alert('Registration error: ' + error.message);
            }
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async function() {
            if (!authToken) return;

            try {
                const response = await fetch(`${API_BASE}/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                displayResponse(data);

                authToken = null;
                currentUser = null;
                localStorage.removeItem('auth_token');
                localStorage.removeItem('current_user');
                updateAuthStatus();
                alert('Logged out successfully!');
            } catch (error) {
                displayResponse({
                    error: error.message
                });
            }
        });

        // Delete recipe
        async function deleteRecipe(id) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            if (!confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/recipes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    alert('Recipe deleted successfully!');
                    loadRecipes(); // Refresh list
                } else {
                    alert('Failed to delete recipe: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
                alert('Error deleting recipe: ' + error.message);
            }
        }

        // Edit recipe
        async function editRecipe(id) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/recipes/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    console.log('Recipe data:', data.data); // Debug log
                    populateUpdateForm(data.data);
                    // Show update tab
                    document.getElementById('update-recipe-tab').style.display = 'block';
                    document.getElementById('update-recipe-tab').click();
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
                alert('Error loading recipe: ' + error.message);
            }
        }

        // Populate update form
        function populateUpdateForm(recipe) {
            document.getElementById('updateRecipeId').value = recipe.id;
            document.getElementById('updateTitle').value = recipe.title;
            document.getElementById('updateCookingTime').value = recipe.cooking_time;
            document.getElementById('updateCategory').value = recipe.category || 'main_course';
            document.getElementById('updateDescription').value = recipe.description || '';

            // Display current images
            let imagesHtml = '';
            if (recipe.thumbnail_url) {
                imagesHtml += `
                    <div class="d-inline-block me-2 mb-2">
                        <img src="${recipe.thumbnail_url}"
                             class="image-preview"
                             alt="Thumbnail"
                             onerror="this.parentElement.innerHTML='<div class=\\"image-error\\">Thumbnail<br>Not Found</div>'">
                        <div class="text-center"><small>Thumbnail</small></div>
                    </div>
                `;
            }
            if (recipe.images && recipe.images.length > 0) {
                recipe.images.forEach((image, index) => {
                    imagesHtml += `
                        <div class="d-inline-block me-2 mb-2">
                            <img src="${image.image_url}"
                                 class="image-preview"
                                 alt="Image ${index + 1}"
                                 onerror="this.parentElement.innerHTML='<div class=\\"image-error\\">Image ${index + 1}<br>Not Found</div>'">
                            <div class="text-center"><small>Image ${index + 1}</small></div>
                        </div>
                    `;
                });
            }
            document.getElementById('currentImagesDisplay').innerHTML = imagesHtml;

            // Populate steps
            const stepsContainer = document.getElementById('updateStepsContainer');
            stepsContainer.innerHTML = '';
            recipe.steps.forEach((step, index) => {
                const stepDiv = document.createElement('div');
                stepDiv.className = 'step-input';
                stepDiv.innerHTML = `
                    <div class="input-group">
                        <span class="input-group-text">${index + 1}</span>
                        <textarea class="form-control" name="steps[${index}][instruction]" required>${step.instruction}</textarea>
                        <button class="btn btn-outline-danger" type="button" onclick="removeUpdateStep(this)">Remove</button>
                    </div>
                `;
                stepsContainer.appendChild(stepDiv);
            });
            updateStepCounter = recipe.steps.length;

            // Populate alats
            const alatsContainer = document.getElementById('updateAlatsContainer');
            alatsContainer.innerHTML = '';
            recipe.alats.forEach((alat, index) => {
                const alatDiv = document.createElement('div');
                alatDiv.className = 'ingredient-row';
                alatDiv.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select" name="alats[${index}][id]">
                                <option value="">Select cooking tool...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="alats[${index}][amount]" value="${alat.pivot.amount || ''}" placeholder="Amount/description">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-danger" type="button" onclick="removeUpdateIngredient(this)">Remove</button>
                        </div>
                    </div>
                `;
                alatsContainer.appendChild(alatDiv);

                // Populate select
                const select = alatDiv.querySelector('select');
                alatsData.forEach(alatData => {
                    const option = document.createElement('option');
                    option.value = alatData.id;
                    option.textContent = alatData.name;
                    option.selected = alatData.id === alat.id;
                    select.appendChild(option);
                });
            });
            updateAlatCounter = recipe.alats.length;

            // Populate bahans
            const bahansContainer = document.getElementById('updateBahansContainer');
            bahansContainer.innerHTML = '';
            recipe.bahans.forEach((bahan, index) => {
                const bahanDiv = document.createElement('div');
                bahanDiv.className = 'ingredient-row';
                bahanDiv.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-select" name="bahans[${index}][id]" onchange="loadUnitsForBahan(this, 'update_${index}')">
                                <option value="">Select ingredient...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="bahans[${index}][amount]" value="${bahan.pivot.amount || ''}" placeholder="Amount (e.g., 500)">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="bahans[${index}][unit]" id="unitSelect_update_${index}">
                                <option value="">Select unit...</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-danger" type="button" onclick="removeUpdateIngredient(this)">Remove</button>
                        </div>
                    </div>
                `;
                bahansContainer.appendChild(bahanDiv);

                // Populate select
                const select = bahanDiv.querySelector('select');
                bahansData.forEach(bahanData => {
                    const option = document.createElement('option');
                    option.value = bahanData.id;
                    option.textContent = bahanData.name;
                    option.selected = bahanData.id === bahan.id;
                    select.appendChild(option);
                });

                // Load units for this bahan and select current unit
                if (bahan.id && window.globalBahansData) {
                    const bahanData = window.globalBahansData.find(b => b.id == bahan.id);
                    if (bahanData && bahanData.units) {
                        populateUnitSelect(`update_${index}`, bahanData.units, bahanData.name);
                        
                        // Set current unit if available
                        setTimeout(() => {
                            const unitSelect = document.getElementById(`unitSelect_update_${index}`);
                            if (unitSelect && bahan.pivot.unit) {
                                unitSelect.value = bahan.pivot.unit;
                            }
                        }, 100);
                    }
                }
            });
            updateBahanCounter = recipe.bahans.length;
        }

        // Cancel update
        function cancelUpdateRecipe() {
            document.getElementById('update-recipe-tab').style.display = 'none';
            document.getElementById('recipes-tab').click();
        }

        // Add update step
        function addUpdateStep() {
            const container = document.getElementById('updateStepsContainer');
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step-input';
            stepDiv.innerHTML = `
                <div class="input-group">
                    <span class="input-group-text">${updateStepCounter + 1}</span>
                    <textarea class="form-control" name="steps[${updateStepCounter}][instruction]" placeholder="Enter cooking step..." required></textarea>
                    <button class="btn btn-outline-danger" type="button" onclick="removeUpdateStep(this)">Remove</button>
                </div>
            `;
            container.appendChild(stepDiv);
            updateStepCounter++;
        }

        // Remove update step
        function removeUpdateStep(button) {
            button.closest('.step-input').remove();
            updateUpdateStepNumbers();
        }

        // Update step numbers for update form
        function updateUpdateStepNumbers() {
            const steps = document.querySelectorAll('#updateStepsContainer .step-input');
            steps.forEach((step, index) => {
                step.querySelector('.input-group-text').textContent = index + 1;
                step.querySelector('textarea').name = `steps[${index}][instruction]`;
            });
            updateStepCounter = steps.length;
        }

        // Add update alat
        function addUpdateAlat() {
            const container = document.getElementById('updateAlatsContainer');
            const alatDiv = document.createElement('div');
            alatDiv.className = 'ingredient-row';
            alatDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" name="alats[${updateAlatCounter}][id]">
                            <option value="">Select cooking tool...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="alats[${updateAlatCounter}][amount]" placeholder="Amount/description">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-danger" type="button" onclick="removeUpdateIngredient(this)">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(alatDiv);

            // Populate the new select
            const newSelect = alatDiv.querySelector('select');
            alatsData.forEach(alat => {
                newSelect.innerHTML += `<option value="${alat.id}">${alat.name}</option>`;
            });

            updateAlatCounter++;
        }

        // Add update bahan
        function addUpdateBahan() {
            const container = document.getElementById('updateBahansContainer');
            const bahanDiv = document.createElement('div');
            bahanDiv.className = 'ingredient-row';
            bahanDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select" name="bahans[${updateBahanCounter}][id]" onchange="loadUnitsForBahan(this, 'update_${updateBahanCounter}')">
                            <option value="">Select ingredient...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="bahans[${updateBahanCounter}][amount]" placeholder="Amount (e.g., 500)">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="bahans[${updateBahanCounter}][unit]" id="unitSelect_update_${updateBahanCounter}">
                            <option value="">Select unit...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-danger" type="button" onclick="removeUpdateIngredient(this)">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(bahanDiv);

            // Populate the new select
            const newSelect = bahanDiv.querySelector('select');
            bahansData.forEach(bahan => {
                newSelect.innerHTML += `<option value="${bahan.id}">${bahan.name}</option>`;
            });

            updateBahanCounter++;
        }

        // Remove update ingredient (alat or bahan)
        function removeUpdateIngredient(button) {
            button.closest('.ingredient-row').remove();
        }

        // Load recipes with pagination
        async function loadRecipes(page = 1) {
            currentRecipesPage = page;
            document.getElementById('recipesLoading').style.display = 'block';
            document.getElementById('recipesList').innerHTML = '';
            document.getElementById('recipesPagination').style.display = 'none';
            
            try {
                // Get search and per page values
                const search = document.getElementById('searchRecipes').value;
                const perPage = document.getElementById('recipesPerPage').value;
                
                // Build URL with parameters
                const params = new URLSearchParams({
                    page: page,
                    per_page: perPage
                });
                
                if (search) {
                    params.append('search', search);
                }
                
                const url = `${API_BASE}/recipes?${params.toString()}`;
                const response = await fetch(url);
                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    displayRecipesList(data.data.data);
                    displayRecipesPagination(data.data);
                } else {
                    document.getElementById('recipesList').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${data.message || 'Failed to load recipes'}
                        </div>
                    `;
                }
            } catch (error) {
                document.getElementById('recipesList').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Error: ${error.message}
                    </div>
                `;
                displayResponse({
                    error: error.message
                });
            } finally {
                document.getElementById('recipesLoading').style.display = 'none';
            }
        }

        // Load newest recipes with pagination
        async function loadNewestRecipes(page = 1) {
            currentNewestPage = page;
            document.getElementById('newestLoading').style.display = 'block';
            document.getElementById('newestRecipesList').innerHTML = '';
            document.getElementById('newestPagination').style.display = 'none';
            
            try {
                console.log('Loading newest recipes...'); // Debug
                
                // Get per page value
                const perPage = document.getElementById('newestPerPage').value;
                
                // Build URL with parameters
                const params = new URLSearchParams({
                    page: page,
                    per_page: perPage
                });
                
                const url = `${API_BASE}/recipes/newest?${params.toString()}`;
                const response = await fetch(url);
                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    displayNewestRecipesList(data.data.data);
                    displayNewestPagination(data.data);
                } else {
                    document.getElementById('newestRecipesList').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${data.message || 'Failed to load newest recipes'}
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading newest recipes:', error);
                document.getElementById('newestRecipesList').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Error: ${error.message}
                    </div>
                `;
                displayResponse({
                    error: error.message
                });
            } finally {
                document.getElementById('newestLoading').style.display = 'none';
            }
        }

        // Display recipes list
        function displayRecipesList(recipes) {
            const container = document.getElementById('recipesList');

            if (recipes.length === 0) {
                container.innerHTML = '<p class="text-muted">No recipes found.</p>';
                return;
            }

            let html = '';
            recipes.forEach(recipe => {
                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="${recipe.thumbnail_url}"
                                         class="img-fluid rounded"
                                         alt="Recipe thumbnail"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title">${recipe.title}</h5>
                                    <p class="card-text">${recipe.description || 'No description'}</p>
                                    <p class="text-muted">
                                        <small>
                                            By: ${recipe.user.name} |
                                            Cooking time: ${recipe.cooking_time} minutes |
                                            Favorites: ${recipe.favorites_count}
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">View</button>
                                        ${authToken ? `
                                                <button class="btn btn-sm btn-outline-success" onclick="addToFavorites(${recipe.id})">
                                                    <i class="fas fa-heart"></i> Favorite
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromFavorites(${recipe.id})">
                                                    <i class="fas fa-heart-broken"></i> Unfavorite
                                                </button>
                                            ` : ''}
                                        ${authToken && currentUser && currentUser.id === recipe.user_id ? `
                                                <button class="btn btn-sm btn-outline-warning" onclick="editRecipe(${recipe.id})">Edit</button>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="deleteRecipe(${recipe.id})">Delete</button>
                                            ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Display newest recipes list (enhanced version with special styling)
        function displayNewestRecipesList(recipes) {
            const container = document.getElementById('newestRecipesList');

            if (recipes.length === 0) {
                container.innerHTML = '<p class="text-muted">No newest recipes found.</p>';
                return;
            }

            let html = '';
            recipes.forEach((recipe, index) => {
                // Add badge for newest recipes
                const badge = index < 3 ? '<span class="badge bg-danger ms-2">🔥 Hot</span>' : '';
                
                html += `
                    <div class="card mb-3 ${index === 0 ? 'border-warning' : ''}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="${recipe.thumbnail_url}"
                                         class="img-fluid rounded"
                                         alt="Recipe thumbnail"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title">
                                        ${recipe.title}${badge}
                                        ${index === 0 ? ' <span class="badge bg-success">Latest</span>' : ''}
                                    </h5>
                                    <p class="card-text">${recipe.description || 'No description'}</p>
                                    <p class="text-muted">
                                        <small>
                                            <i class="fas fa-user"></i> By: ${recipe.user.name} |
                                            <i class="fas fa-clock"></i> ${recipe.cooking_time} minutes |
                                            <i class="fas fa-heart"></i> ${recipe.favorites_count} favorites |
                                            <i class="fas fa-calendar"></i> ${new Date(recipe.created_at).toLocaleDateString()}
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        ${authToken ? `
                                                <button class="btn btn-sm btn-outline-success" onclick="addToFavorites(${recipe.id})">
                                                    <i class="fas fa-heart"></i> Favorite
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromFavorites(${recipe.id})">
                                                    <i class="fas fa-heart-broken"></i> Unfavorite
                                                </button>
                                            ` : ''}
                                        ${authToken && currentUser && currentUser.id === recipe.user_id ? `
                                                <button class="btn btn-sm btn-outline-warning" onclick="editRecipe(${recipe.id})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="deleteRecipe(${recipe.id})">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Load recipes by category
        async function loadAppetizerRecipes() {
            await loadRecipesByCategory('appetizer', 'appetizerRecipesList');
        }

        async function loadMainCourseRecipes() {
            await loadRecipesByCategory('main_course', 'mainCourseRecipesList');
        }

        async function loadDessertRecipes() {
            await loadRecipesByCategory('dessert', 'dessertRecipesList');
        }

        async function loadRecipesByCategory(category, containerId) {
            try {
                console.log(`Loading ${category} recipes...`);
                
                const response = await fetch(`${API_BASE}/recipes/category/${category}`);
                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    displayCategoryRecipesList(data.data.data, containerId, category);
                } else {
                    document.getElementById(containerId).innerHTML = 
                        `<p class="text-danger">Error: ${data.message}</p>`;
                }
            } catch (error) {
                console.error(`Error loading ${category} recipes:`, error);
                document.getElementById(containerId).innerHTML = 
                    `<p class="text-danger">Error: ${error.message}</p>`;
                displayResponse({
                    error: error.message
                });
            }
        }

        function displayCategoryRecipesList(recipes, containerId, category) {
            const container = document.getElementById(containerId);
            
            if (recipes.length === 0) {
                const categoryName = category === 'main_course' ? 'main course' : category;
                container.innerHTML = `<p class="text-muted">No ${categoryName} recipes found.</p>`;
                return;
            }

            let html = '';
            recipes.forEach(recipe => {
                const categoryIcon = category === 'appetizer' ? '🥗' : 
                                   category === 'main_course' ? '🍽️' : '🍰';
                
                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    ${recipe.thumbnail ? 
                                        `<img src="${recipe.thumbnail_url}" class="img-fluid rounded" alt="Recipe Thumbnail">` :
                                        `<div class="image-error d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>`
                                    }
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title">
                                        ${categoryIcon} ${recipe.title}
                                        <span class="badge bg-primary ms-2">${category.replace('_', ' ')}</span>
                                    </h5>
                                    <p class="card-text">${recipe.description || 'No description'}</p>
                                    <p class="text-muted">
                                        <small>
                                            <i class="fas fa-user"></i> ${recipe.user.name} |
                                            <i class="fas fa-clock"></i> ${recipe.cooking_time} minutes |
                                            <i class="fas fa-heart"></i> ${recipe.favorites_count || 0} favorites
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        ${authToken ? `
                                                <button class="btn btn-sm btn-outline-success" onclick="addToFavorites(${recipe.id})">
                                                    <i class="fas fa-heart"></i> Favorite
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromFavorites(${recipe.id})">
                                                    <i class="fas fa-heart-broken"></i> Unfavorite
                                                </button>
                                            ` : ''}
                                        ${authToken && currentUser && currentUser.id === recipe.user_id ? `
                                                <button class="btn btn-sm btn-outline-warning" onclick="editRecipe(${recipe.id})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="deleteRecipe(${recipe.id})">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // View recipe detail
        async function viewRecipe(id) {
            try {
                const headers = {};
                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(`${API_BASE}/recipes/${id}`, {
                    headers
                });
                const data = await response.json();
                displayResponse(data);
            } catch (error) {
                displayResponse({
                    error: error.message
                });
            }
        }

        // Toggle favorite
        async function toggleFavorite(recipeId, isFavorited) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const method = isFavorited ? 'DELETE' : 'POST';
                const actionText = isFavorited ? 'Unfavorited' : 'Favorited';

                const response = await fetch(`${API_BASE}/recipes/${recipeId}/favorite`, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    // Show success message
                    alert(`Recipe ${actionText} successfully!`);

                    // Refresh lists
                    loadRecipes();
                    loadFavorites();

                    // If favorited, suggest checking notifications
                    if (!isFavorited) {
                        setTimeout(() => {
                            if (confirm(
                                    'Recipe favorited! Would you like to check notifications to see if the recipe owner gets notified?'
                                    )) {
                                // Switch to notifications tab
                                document.getElementById('notifications-tab').click();
                                setTimeout(() => {
                                    loadNotifications();
                                }, 500);
                            }
                        }, 1000);
                    }
                } else if (response.status === 409 && data.data && data.data.action === 'already_favorited') {
                    // Recipe already favorited - show appropriate message and refresh
                    alert('Recipe is already in your favorites! Click "Unfavorite" to remove it.');
                    loadRecipes(); // This will update the button to show "Unfavorite"
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
                displayResponse({
                    error: error.message
                });
                alert('Network error: ' + error.message);
            }
        }

        // Add to favorites (separate function)
        async function addToFavorites(recipeId) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/recipes/${recipeId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    alert('Recipe added to favorites successfully!');
                    loadRecipes();
                    loadFavorites();

                    // Ask if user wants to check notifications
                    setTimeout(() => {
                        if (confirm(
                                'Recipe favorited! Would you like to check notifications to see if the recipe owner gets notified?'
                                )) {
                            document.getElementById('notifications-tab').click();
                            setTimeout(() => {
                                loadNotifications();
                            }, 500);
                        }
                    }, 1000);
                } else if (response.status === 409) {
                    alert('Recipe is already in your favorites! Use "Unfavorite" button to remove it.');
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error adding to favorites:', error);
                displayResponse({
                    error: error.message
                });
                alert('Network error: ' + error.message);
            }
        }

        // Remove from favorites (separate function)
        async function removeFromFavorites(recipeId) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            if (!confirm('Are you sure you want to remove this recipe from your favorites?')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/recipes/${recipeId}/favorite`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    alert('Recipe removed from favorites successfully!');
                    loadRecipes();
                    loadFavorites();
                } else if (response.status === 404) {
                    alert('Recipe is not in your favorites!');
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error removing from favorites:', error);
                displayResponse({
                    error: error.message
                });
                alert('Network error: ' + error.message);
            }
        }

        // Load favorites
        async function loadFavorites() {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/favorites`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    displayFavoritesList(data.data.data);
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
            }
        }

        // Display favorites list
        function displayFavoritesList(favorites) {
            const container = document.getElementById('favoritesList');

            if (favorites.length === 0) {
                container.innerHTML = '<p class="text-muted">No favorite recipes yet.</p>';
                return;
            }

            let html = '';
            favorites.forEach(recipe => {
                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">${recipe.title}</h5>
                            <p class="card-text">${recipe.description || 'No description'}</p>
                            <p class="text-muted">
                                <small>Cooking time: ${recipe.cooking_time} minutes</small>
                            </p>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">View</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromFavorites(${recipe.id})">
                                <i class="fas fa-heart-broken"></i> Remove from Favorites
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Load master data
        async function loadMasterData() {
            await loadAlats();
            await loadBahans();
            populateSelects();
        }

        // Load alats
        async function loadAlats() {
            try {
                const response = await fetch(`${API_BASE}/alats`);
                const data = await response.json();

                if (data.success) {
                    alatsData = data.data;
                    displayAlatsList(data.data);
                    populateSelects();
                }
            } catch (error) {
                console.error('Error loading alats:', error);
            }
        }

        // Load bahans
        async function loadBahans() {
            try {
                const response = await fetch(`${API_BASE}/bahans`);
                const data = await response.json();

                if (data.success) {
                    bahansData = data.data;
                    displayBahansList(data.data);
                    populateSelects();
                    
                    // Store bahans data globally for unit loading
                    window.globalBahansData = data.data;
                }
            } catch (error) {
                console.error('Error loading bahans:', error);
            }
        }

        // Load units for specific bahan
        async function loadUnitsForBahan(selectElement, index) {
            const bahanId = selectElement.value;
            if (!bahanId) {
                // Clear unit select if no bahan selected
                const unitSelect = document.getElementById(`unitSelect_${index}`);
                if (unitSelect) {
                    unitSelect.innerHTML = '<option value="">Select unit...</option>';
                }
                return;
            }

            try {
                // Find bahan data from global data to avoid API call
                const bahan = window.globalBahansData?.find(b => b.id == bahanId);
                if (bahan && bahan.units) {
                    populateUnitSelect(index, bahan.units, bahan.name);
                } else {
                    // Fallback to API call
                    const response = await fetch(`${API_BASE}/bahans/${bahanId}/units`);
                    const data = await response.json();
                    
                    if (data.success) {
                        populateUnitSelect(index, data.data.units, data.data.bahan);
                    }
                }
            } catch (error) {
                console.error('Error loading units for bahan:', error);
            }
        }

        // Populate unit select dropdown
        function populateUnitSelect(index, units, bahanName) {
            const unitSelect = document.getElementById(`unitSelect_${index}`);
            if (!unitSelect) return;

            let html = '<option value="">Select unit...</option>';
            
            if (units && Array.isArray(units)) {
                units.forEach(unit => {
                    html += `<option value="${unit}">${unit}</option>`;
                });
            }
            
            unitSelect.innerHTML = html;
            
            // Show helper text
            console.log(`Units loaded for ${bahanName}:`, units);
        }

        // Load all available units (for reference)
        async function loadAllUnits() {
            try {
                const response = await fetch(`${API_BASE}/units`);
                const data = await response.json();
                
                if (data.success) {
                    window.globalUnitsData = data.data;
                    console.log('All units loaded:', data.data);
                }
            } catch (error) {
                console.error('Error loading all units:', error);
            }
        }

        // Test units API functionality
        async function testUnitsAPI() {
            try {
                const response = await fetch(`${API_BASE}/units`);
                const data = await response.json();
                displayResponse(data);
                
                if (data.success) {
                    displayUnitsList(data.data);
                }
            } catch (error) {
                console.error('Error testing units API:', error);
                displayResponse({ error: error.message });
            }
        }

        // Test specific bahan units
        async function testBahanUnits() {
            try {
                const bahanId = 1; // Test with bahan ID 1
                const response = await fetch(`${API_BASE}/bahans/${bahanId}/units`);
                const data = await response.json();
                displayResponse(data);
                
                if (data.success) {
                    const container = document.getElementById('unitsList');
                    container.innerHTML = `
                        <div class="alert alert-info">
                            <h6>Units for: ${data.data.bahan}</h6>
                            <ul class="mb-0">
                                ${data.data.units.map(unit => `<li>${unit}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error testing bahan units:', error);
                displayResponse({ error: error.message });
            }
        }

        // Display units list
        function displayUnitsList(unitsData) {
            const container = document.getElementById('unitsList');
            let html = '';
            
            Object.keys(unitsData).forEach(category => {
                const categoryData = unitsData[category];
                html += `
                    <div class="mb-3">
                        <h6 class="text-primary">${categoryData.label}</h6>
                        <div class="row">
                            ${categoryData.units.map(unit => 
                                `<div class="col-6"><span class="badge bg-light text-dark">${unit}</span></div>`
                            ).join('')}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Display alats list
        function displayAlatsList(alats) {
            const container = document.getElementById('alatsList');
            let html = '<ul class="list-group">';
            alats.forEach(alat => {
                html += `<li class="list-group-item">${alat.name}</li>`;
            });
            html += '</ul>';
            container.innerHTML = html;
        }

        // Display bahans list
        function displayBahansList(bahans) {
            const container = document.getElementById('bahansList');
            let html = '<ul class="list-group">';
            bahans.forEach(bahan => {
                html += `<li class="list-group-item">${bahan.name}</li>`;
            });
            html += '</ul>';
            container.innerHTML = html;
        }

        // Populate select dropdowns
        function populateSelects() {
            // Populate alat selects
            const alatSelects = document.querySelectorAll('select[name*="alats"]');
            alatSelects.forEach(select => {
                select.innerHTML = '<option value="">Select cooking tool...</option>';
                alatsData.forEach(alat => {
                    select.innerHTML += `<option value="${alat.id}">${alat.name}</option>`;
                });
            });

            // Populate bahan selects
            const bahanSelects = document.querySelectorAll('select[name*="bahans"]');
            bahanSelects.forEach(select => {
                select.innerHTML = '<option value="">Select ingredient...</option>';
                bahansData.forEach(bahan => {
                    select.innerHTML += `<option value="${bahan.id}">${bahan.name}</option>`;
                });
            });
        }

        // Add step
        function addStep() {
            const container = document.getElementById('stepsContainer');
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step-input';
            stepDiv.innerHTML = `
                <div class="input-group">
                    <span class="input-group-text">${stepCounter + 1}</span>
                    <textarea class="form-control" name="steps[${stepCounter}][instruction]" placeholder="Enter cooking step..." required></textarea>
                    <button class="btn btn-outline-danger" type="button" onclick="removeStep(this)">Remove</button>
                </div>
            `;
            container.appendChild(stepDiv);
            stepCounter++;
        }

        // Remove step
        function removeStep(button) {
            button.closest('.step-input').remove();
            updateStepNumbers();
        }

        // Update step numbers
        function updateStepNumbers() {
            const steps = document.querySelectorAll('#stepsContainer .step-input');
            steps.forEach((step, index) => {
                step.querySelector('.input-group-text').textContent = index + 1;
                step.querySelector('textarea').name = `steps[${index}][instruction]`;
            });
            stepCounter = steps.length;
        }

        // Add alat
        function addAlat() {
            const container = document.getElementById('alatsContainer');
            const alatDiv = document.createElement('div');
            alatDiv.className = 'ingredient-row';
            alatDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" name="alats[${alatCounter}][id]">
                            <option value="">Select cooking tool...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="alats[${alatCounter}][amount]" placeholder="Amount/description">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-danger" type="button" onclick="removeIngredient(this)">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(alatDiv);

            // Populate the new select
            const newSelect = alatDiv.querySelector('select');
            alatsData.forEach(alat => {
                newSelect.innerHTML += `<option value="${alat.id}">${alat.name}</option>`;
            });

            alatCounter++;
        }

        // Add bahan
        function addBahan() {
            const container = document.getElementById('bahansContainer');
            const bahanDiv = document.createElement('div');
            bahanDiv.className = 'ingredient-row';
            bahanDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select" name="bahans[${bahanCounter}][id]" onchange="loadUnitsForBahan(this, ${bahanCounter})">
                            <option value="">Select ingredient...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="bahans[${bahanCounter}][amount]" placeholder="Amount (e.g., 500)">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="bahans[${bahanCounter}][unit]" id="unitSelect_${bahanCounter}">
                            <option value="">Select unit...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-danger" type="button" onclick="removeIngredient(this)">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(bahanDiv);

            // Populate the new select
            const newSelect = bahanDiv.querySelector('select');
            bahansData.forEach(bahan => {
                newSelect.innerHTML += `<option value="${bahan.id}">${bahan.name}</option>`;
            });

            bahanCounter++;
        }

        // Remove ingredient (alat or bahan)
        function removeIngredient(button) {
            button.closest('.ingredient-row').remove();
        }

        // Create recipe form handler
        document.getElementById('createRecipeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!authToken) {
                alert('Please login first!');
                return;
            }

            const formData = new FormData(this);

            try {
                const response = await fetch(`${API_BASE}/recipes`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: formData
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    alert('Recipe created successfully!');
                    this.reset();
                    // Reset counters
                    stepCounter = 1;
                    alatCounter = 1;
                    bahanCounter = 1;
                    // Reset containers to have one item each
                    document.getElementById('stepsContainer').innerHTML = `
                        <div class="step-input">
                            <div class="input-group">
                                <span class="input-group-text">1</span>
                                <textarea class="form-control" name="steps[0][instruction]" placeholder="Enter cooking step..." required></textarea>
                                <button class="btn btn-outline-danger" type="button" onclick="removeStep(this)">Remove</button>
                            </div>
                        </div>
                    `;
                    loadMasterData(); // Repopulate selects
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
            }
        });

        // Update recipe form handler
        document.getElementById('updateRecipeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!authToken) {
                alert('Please login first!');
                return;
            }

            const recipeId = document.getElementById('updateRecipeId').value;
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Laravel method override

            // Debug: Log form data
            console.log('Update form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            try {
                const response = await fetch(`${API_BASE}/recipes/${recipeId}`, {
                    method: 'POST', // Using POST with _method override for file uploads
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: formData
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    alert('Recipe updated successfully!');
                    cancelUpdateRecipe();
                    loadRecipes(); // Refresh list
                } else {
                    alert('Failed to update recipe: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
                alert('Error updating recipe: ' + error.message);
            }
        });

        // Search recipes
        document.getElementById('searchRecipes').addEventListener('input', function(e) {
            handleRecipesSearch();
        });

        // Load notifications
        async function loadNotifications() {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const unreadOnly = document.getElementById('unreadOnlyFilter').checked;
                const url = unreadOnly ? `${API_BASE}/notifications?unread_only=true` : `${API_BASE}/notifications`;

                console.log('Loading notifications from:', url); // Debug

                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Response status:', response.status); // Debug
                console.log('Response headers:', [...response.headers.entries()]); // Debug

                const responseText = await response.text();
                console.log('Raw response:', responseText); // Debug

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('JSON Parse Error:', parseError);
                    console.error('Response was:', responseText);
                    displayResponse({
                        error: 'Invalid JSON response from server',
                        raw_response: responseText.substring(0, 500)
                    });
                    return;
                }

                displayResponse(data);

                if (data.success) {
                    displayNotificationsList(data.data.data);
                } else {
                    console.error('API Error:', data);
                }
            } catch (error) {
                console.error('Network Error:', error);
                displayResponse({
                    error: error.message
                });
            }
        }

        // Helper function to generate profile picture HTML
        function getProfilePictureHtml(fromUser) {
            if (!fromUser) {
                return `<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-white"></i>
                        </div>`;
            }

            const profilePic = fromUser.profile_picture;
            const userName = fromUser.name || 'Unknown';

            // Debug log
            console.log('User:', userName, 'Profile picture:', profilePic);

            // Special case for pata ahmad who has real profile picture
            if (profilePic && profilePic.includes('1752591825_27.jpg')) {
                return `<img src="${profilePic}" class="rounded-circle"
                             style="width: 50px; height: 50px; object-fit: cover; border: 2px solid green;"
                             alt="${userName}" title="${userName}">`;
            } else {
                return `<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;" title="${userName}">
                            <i class="fas fa-user text-white"></i>
                        </div>`;
            }
        }

        // Display notifications list
        function displayNotificationsList(notifications) {
            const container = document.getElementById('notificationsList');

            if (notifications.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No notifications found.</div>';
                return;
            }

            let html = '<div class="list-group">';
            notifications.forEach(notification => {
                const isRead = notification.is_read;
                const bgClass = isRead ? 'list-group-item-light' : 'list-group-item-primary';

                html += `
                    <div class="list-group-item ${bgClass} d-flex align-items-center p-3">
                        <!-- User Avatar -->
                        <div class="me-3">
                            ${getProfilePictureHtml(notification.from_user)}
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <strong>${notification.from_user ? notification.from_user.name : 'Unknown User'}</strong>
                                        menambahkan resep anda ke favorit
                                    </h6>
                                    <p class="mb-1 text-muted small">
                                        ${notification.recipe ? notification.recipe.title : 'Unknown Recipe'}
                                    </p>
                                    <small class="text-muted">${notification.time_ago || new Date(notification.created_at).toLocaleString()}</small>
                                </div>

                                <!-- Recipe Thumbnail -->
                                <div class="ms-3">
                                    ${notification.recipe && notification.recipe.thumbnail ?
                                        `<img src="${notification.recipe.thumbnail}" class="rounded"
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="Recipe thumbnail">` :
                                        `<div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-utensils text-muted"></i>
                                             </div>`
                                    }
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="ms-3 btn-group-vertical btn-group-sm">
                            ${!isRead ?
                                `<button class="btn btn-outline-success btn-sm mb-1"
                                             onclick="markAsRead(${notification.id})">
                                        <i class="fas fa-check"></i> Mark Read
                                     </button>` :
                                '<span class="badge bg-success mb-1"><i class="fas fa-check"></i> Read</span>'
                            }
                            <button class="btn btn-outline-danger btn-sm"
                                    onclick="deleteNotification(${notification.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        // Load unread count
        async function loadUnreadCount() {
            if (!authToken) return;

            try {
                console.log('Loading unread count...'); // Debug

                const response = await fetch(`${API_BASE}/notifications/unread-count`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Unread count response status:', response.status); // Debug

                if (!response.ok) {
                    console.error('Unread count request failed:', response.status, response.statusText);
                    return;
                }

                const responseText = await response.text();
                console.log('Unread count raw response:', responseText); // Debug

                const data = JSON.parse(responseText);
                if (data.success) {
                    document.getElementById('unreadCount').textContent = data.data.unread_count;
                } else {
                    console.error('Unread count API error:', data);
                }
            } catch (error) {
                console.error('Error loading unread count:', error);
                // Don't show alert for this, just log
            }
        }

        // Mark notification as read
        async function markAsRead(id) {
            if (!authToken) return;

            try {
                const response = await fetch(`${API_BASE}/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadNotifications();
                    loadUnreadCount();
                }
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        }

        // Mark all as read
        async function markAllAsRead() {
            if (!authToken) return;

            try {
                const response = await fetch(`${API_BASE}/notifications/read-all`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadNotifications();
                    loadUnreadCount();
                    alert('All notifications marked as read!');
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }

        // Delete notification
        async function deleteNotification(id) {
            if (!authToken) return;

            if (!confirm('Delete this notification?')) return;

            try {
                const response = await fetch(`${API_BASE}/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadNotifications();
                    loadUnreadCount();
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
            }
        }

        // Load my recipes
        async function loadMyRecipes() {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const search = document.getElementById('searchMyRecipes').value;
                const url = search ? `${API_BASE}/my-recipes?search=${encodeURIComponent(search)}` :
                    `${API_BASE}/my-recipes`;

                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                displayResponse(data);

                if (data.success) {
                    displayMyRecipesList(data.data.data);
                    loadRecipeStats();
                }
            } catch (error) {
                displayResponse({
                    error: error.message
                });
            }
        }

        // Display my recipes list
        function displayMyRecipesList(recipes) {
            const container = document.getElementById('myRecipesList');

            if (recipes.length === 0) {
                container.innerHTML = '<p class="text-muted">You haven\'t created any recipes yet.</p>';
                return;
            }

            let html = '';
            recipes.forEach(recipe => {
                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="${recipe.thumbnail_url}"
                                         class="img-fluid rounded"
                                         alt="Recipe thumbnail"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title">${recipe.title}</h5>
                                    <p class="card-text">${recipe.description || 'No description'}</p>
                                    <p class="text-muted">
                                        <small>
                                            Cooking time: ${recipe.cooking_time} minutes |
                                            Favorites: ${recipe.favorites_count} |
                                            Created: ${new Date(recipe.created_at).toLocaleDateString()}
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">View</button>
                                        <button class="btn btn-sm btn-outline-success" onclick="editRecipe(${recipe.id})">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRecipe(${recipe.id})">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Load recipe stats
        async function loadRecipeStats() {
            if (!authToken) return;

            try {
                const response = await fetch(`${API_BASE}/my-recipes/stats`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById('recipeStats').innerHTML = `
                        <small>
                            Total Recipes: ${data.data.total_recipes} |
                            Total Favorites Received: ${data.data.total_favorites_received}
                        </small>
                    `;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Search my recipes
        document.getElementById('searchMyRecipes').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            if (searchTerm.length > 2 || searchTerm.length === 0) {
                loadMyRecipes();
            }
        });

        // ===== EXPLORE FUNCTIONS =====
        
        let currentExploreCategory = 'all';
        let currentExplorePage = 1;
        let exploreSearchTimeout;
        
        // Load explore groups with pagination and filters
        async function loadExploreGroups(page = 1) {
            currentExplorePage = page;
            document.getElementById('exploreLoading').style.display = 'block';
            document.getElementById('exploreContent').innerHTML = '';
            document.getElementById('explorePagination').style.display = 'none';
            
            try {
                // Get filter values
                const search = document.getElementById('exploreSearchInput').value;
                const sortBy = document.getElementById('exploreSortBy').value;
                const minRecipes = document.getElementById('exploreMinRecipes').value;
                const perPage = document.getElementById('explorePerPage').value;
                
                // Build URL with parameters
                const params = new URLSearchParams({
                    page: page,
                    per_page: perPage,
                    sort_by: sortBy,
                    min_recipes: minRecipes
                });
                
                if (search) {
                    params.append('search', search);
                }
                
                const baseUrl = currentExploreCategory === 'all' 
                    ? `${API_BASE}/explore/groups`
                    : `${API_BASE}/explore/category/${currentExploreCategory}`;
                    
                const url = `${baseUrl}?${params.toString()}`;
                    
                const response = await fetch(url);
                const data = await response.json();
                displayResponse(data);
                
                if (data.success) {
                    displayExploreGroups(data.data);
                    displayExplorePagination(data.data.pagination);
                } else {
                    document.getElementById('exploreContent').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${data.message || 'Failed to load explore data'}
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading explore groups:', error);
                document.getElementById('exploreContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Error: ${error.message}
                    </div>
                `;
                displayResponse({ error: error.message });
            } finally {
                document.getElementById('exploreLoading').style.display = 'none';
            }
        }
        
        // Handle search with debounce
        function handleExploreSearch() {
            clearTimeout(exploreSearchTimeout);
            exploreSearchTimeout = setTimeout(() => {
                loadExploreGroups(1);
            }, 500);
        }
        
        // Clear search
        function clearExploreSearch() {
            document.getElementById('exploreSearchInput').value = '';
            loadExploreGroups(1);
        }
        
        // Display pagination
        function displayExplorePagination(pagination) {
            const container = document.getElementById('explorePagination');
            
            if (!pagination || pagination.total_pages <= 1) {
                container.style.display = 'none';
                return;
            }
            
            let html = '<nav aria-label="Explore pagination"><ul class="pagination">';
            
            // Previous button
            html += `
                <li class="page-item ${!pagination.has_prev ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.has_prev ? `loadExploreGroups(${pagination.current_page - 1})` : 'return false'}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
            
            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);
            
            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadExploreGroups(1)">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadExploreGroups(${i})">${i}</a>
                    </li>
                `;
            }
            
            if (endPage < pagination.total_pages) {
                if (endPage < pagination.total_pages - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadExploreGroups(${pagination.total_pages})">${pagination.total_pages}</a></li>`;
            }
            
            // Next button
            html += `
                <li class="page-item ${!pagination.has_next ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.has_next ? `loadExploreGroups(${pagination.current_page + 1})` : 'return false'}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
            
            html += '</ul></nav>';
            
            // Page info
            html += `
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing page ${pagination.current_page} of ${pagination.total_pages} 
                        (${pagination.total_groups} groups total)
                    </small>
                </div>
            `;
            
            container.innerHTML = html;
            container.style.display = 'block';
        }
        
        // Display explore groups
        function displayExploreGroups(data) {
            const container = document.getElementById('exploreContent');
            
            if (!data.groups || data.groups.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Belum ada grup yang ditemukan</strong><br>
                        Minimal 2 resep dengan kata pertama yang sama diperlukan untuk membentuk grup.
                        <br><br>
                        <small>Total resep: ${data.total_recipes || 0}</small>
                    </div>
                `;
                return;
            }
            
            let html = `
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <i class="fas fa-chart-bar"></i>
                            <strong>Ditemukan ${data.total_groups} grup</strong> dari ${data.total_recipes} resep total
                        </div>
                    </div>
                </div>
            `;
            
            data.groups.forEach((group, index) => {
                const categoryIcon = group.primary_category === 'appetizer' ? '🥗' : 
                                   group.primary_category === 'main_course' ? '🍽️' : 
                                   group.primary_category === 'dessert' ? '🍰' : '🍴';
                
                const categoryName = group.primary_category === 'main_course' ? 'Main Course' : 
                                   group.primary_category === 'appetizer' ? 'Appetizer' :
                                   group.primary_category === 'dessert' ? 'Dessert' : 'Mixed';
                
                html += `
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0">
                                        ${categoryIcon} ${group.display_name}
                                        <span class="badge bg-light text-dark ms-2">${group.count} resep</span>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <small>
                                        <i class="fas fa-tag"></i> ${categoryName}
                                    </small>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="btn btn-sm btn-light" onclick="viewAllGroupRecipes('${group.keyword}')">
                                        <i class="fas fa-external-link-alt"></i> Lihat Semua
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Category Distribution -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-chart-pie"></i> Distribusi: 
                                    ${group.category_distribution.appetizer > 0 ? `🥗 ${group.category_distribution.appetizer}` : ''}
                                    ${group.category_distribution.main_course > 0 ? `🍽️ ${group.category_distribution.main_course}` : ''}
                                    ${group.category_distribution.dessert > 0 ? `🍰 ${group.category_distribution.dessert}` : ''}
                                </small>
                            </div>
                            
                            <!-- Recipe Cards Preview (max 4) -->
                            <div class="row">
                `;
                
                // Show max 4 recipes as preview
                const previewRecipes = group.recipes.slice(0, 4);
                previewRecipes.forEach(recipe => {
                    html += `
                        <div class="col-md-6 mb-2">
                            <div class="card h-100 border-light">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="${recipe.thumbnail_url}" 
                                                 class="img-fluid rounded" 
                                                 style="height: 60px; width: 100%; object-fit: cover;"
                                                 alt="${recipe.title}"
                                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4='">
                                        </div>
                                        <div class="col-8">
                                            <h6 class="card-title mb-1" style="font-size: 0.85em;">${recipe.title}</h6>
                                            <p class="card-text mb-1" style="font-size: 0.75em;">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i> ${recipe.cooking_time} min
                                                    <i class="fas fa-user ms-1"></i> ${recipe.user.name}
                                                </small>
                                            </p>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewRecipe(${recipe.id})">
                                                View
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                if (group.recipes.length > 4) {
                    html += `
                        <div class="col-12">
                            <div class="text-center">
                                <small class="text-muted">
                                    ... dan ${group.recipes.length - 4} resep lainnya
                                </small>
                            </div>
                        </div>
                    `;
                }
                
                html += `
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        // Filter explore by category
        function filterExploreByCategory(category) {
            // Update active button
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Update current category and reload from page 1
            currentExploreCategory = category;
            loadExploreGroups(1);
        }
        
        // View all recipes in a group (opens new page)
        function viewAllGroupRecipes(keyword) {
            // For now, show in modal or alert
            // Later can be implemented as separate page
            alert(`Coming soon: View all recipes for "${keyword}"`);
            
            // TODO: Implement new page or modal for detailed group view
            // window.open(`/explore/${keyword}`, '_blank');
        }
        
        // ===== RECIPES PAGINATION FUNCTIONS =====
        
        // Display recipes pagination
        function displayRecipesPagination(pagination) {
            const container = document.getElementById('recipesPagination');
            
            if (!pagination || pagination.last_page <= 1) {
                container.style.display = 'none';
                return;
            }
            
            let html = '<nav aria-label="Recipes pagination"><ul class="pagination">';
            
            // Previous button
            html += `
                <li class="page-item ${!pagination.prev_page_url ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.prev_page_url ? `loadRecipes(${pagination.current_page - 1})` : 'return false'}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
            
            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
            
            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadRecipes(1)">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadRecipes(${i})">${i}</a>
                    </li>
                `;
            }
            
            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadRecipes(${pagination.last_page})">${pagination.last_page}</a></li>`;
            }
            
            // Next button
            html += `
                <li class="page-item ${!pagination.next_page_url ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.next_page_url ? `loadRecipes(${pagination.current_page + 1})` : 'return false'}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
            
            html += '</ul></nav>';
            
            // Page info
            html += `
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing ${pagination.from} to ${pagination.to} of ${pagination.total} recipes
                        (Page ${pagination.current_page} of ${pagination.last_page})
                    </small>
                </div>
            `;
            
            container.innerHTML = html;
            container.style.display = 'block';
        }
        
        // Display newest recipes pagination
        function displayNewestPagination(pagination) {
            const container = document.getElementById('newestPagination');
            
            if (!pagination || pagination.last_page <= 1) {
                container.style.display = 'none';
                return;
            }
            
            let html = '<nav aria-label="Newest recipes pagination"><ul class="pagination">';
            
            // Previous button
            html += `
                <li class="page-item ${!pagination.prev_page_url ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.prev_page_url ? `loadNewestRecipes(${pagination.current_page - 1})` : 'return false'}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
            
            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
            
            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadNewestRecipes(1)">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadNewestRecipes(${i})">${i}</a>
                    </li>
                `;
            }
            
            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadNewestRecipes(${pagination.last_page})">${pagination.last_page}</a></li>`;
            }
            
            // Next button
            html += `
                <li class="page-item ${!pagination.next_page_url ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="${pagination.next_page_url ? `loadNewestRecipes(${pagination.current_page + 1})` : 'return false'}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
            
            html += '</ul></nav>';
            
            // Page info
            html += `
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing ${pagination.from} to ${pagination.to} of ${pagination.total} newest recipes
                        (Page ${pagination.current_page} of ${pagination.last_page})
                    </small>
                </div>
            `;
            
            container.innerHTML = html;
            container.style.display = 'block';
        }
        
        // Handle search with debounce for recipes
        function handleRecipesSearch() {
            clearTimeout(recipesSearchTimeout);
            recipesSearchTimeout = setTimeout(() => {
                loadRecipes(1);
            }, 500);
        }
        
        // Load explore data when tab is clicked
        document.getElementById('explore-tab').addEventListener('click', function() {
            // Only load if not already loaded
            if (document.getElementById('exploreContent').innerHTML === '') {
                loadExploreGroups();
            }
        });
    </script>
</body>

</html>
