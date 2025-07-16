<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe API Tester - Luwe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card { margin-bottom: 20px; }
        .response-area { 
            background-color: #f8f9fa; 
            border: 1px solid #dee2e6; 
            border-radius: 5px; 
            padding: 15px; 
            max-height: 400px; 
            overflow-y: auto; 
        }
        .step-input { margin-bottom: 10px; }
        .ingredient-row { margin-bottom: 10px; }
        .nav-tabs .nav-link.active { color: #495057; background-color: #fff; border-color: #dee2e6 #dee2e6 #fff; }
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
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="logoutBtn" style="display: none;">Logout</button>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="auth-tab" data-bs-toggle="tab" data-bs-target="#auth" type="button">Authentication</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recipes-tab" data-bs-toggle="tab" data-bs-target="#recipes" type="button">Recipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="create-recipe-tab" data-bs-toggle="tab" data-bs-target="#create-recipe" type="button">Create Recipe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="update-recipe-tab" data-bs-toggle="tab" data-bs-target="#update-recipe" type="button" style="display: none;">Update Recipe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites" type="button">Favorites</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button">Notifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="my-recipes-tab" data-bs-toggle="tab" data-bs-target="#my-recipes" type="button">My Recipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="master-data-tab" data-bs-toggle="tab" data-bs-target="#master-data" type="button">Master Data</button>
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
                                        <input type="email" id="loginEmail" class="form-control" name="email" value="test@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="loginPassword" class="form-control" name="password" value="password" required>
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
                                        <input type="text" id="registerName" class="form-control" name="name" value="Test User" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="registerEmail" class="form-control" name="email" value="newuser@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="registerPassword" class="form-control" name="password" value="password123" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="registerPasswordConfirm" class="form-control" name="password_confirmation" value="password123" required>
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
                            <div class="mb-3">
                                <input type="text" class="form-control" id="searchRecipes" placeholder="Search recipes...">
                            </div>
                            <div id="recipesList"></div>
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
                                            <input type="number" class="form-control" name="cooking_time" min="1" required>
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
                                            <input type="file" class="form-control" name="thumbnail" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Additional Images (max 4)</label>
                                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
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
                                                <button class="btn btn-outline-danger" type="button" onclick="removeStep(this)">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addStep()">Add Step</button>
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
                                                    <input type="text" class="form-control" name="alats[0][amount]" placeholder="Amount/description">
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-outline-danger" type="button" onclick="removeIngredient(this)">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAlat()">Add Tool</button>
                                </div>

                                <!-- Bahans -->
                                <div class="mb-3">
                                    <label class="form-label">Ingredients (Bahan)</label>
                                    <div id="bahansContainer">
                                        <div class="ingredient-row">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-select" name="bahans[0][id]">
                                                        <option value="">Select ingredient...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="bahans[0][amount]" placeholder="Amount (e.g., 2 cups, 500g)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-outline-danger" type="button" onclick="removeIngredient(this)">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBahan()">Add Ingredient</button>
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
                            <button type="button" class="btn btn-secondary" onclick="cancelUpdateRecipe()">Cancel</button>
                        </div>
                        <div class="card-body">
                            <form id="updateRecipeForm" enctype="multipart/form-data">
                                <input type="hidden" id="updateRecipeId" name="recipe_id">
                                
                                <!-- Basic Info -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Recipe Title</label>
                                            <input type="text" class="form-control" id="updateTitle" name="title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Cooking Time (minutes)</label>
                                            <input type="number" class="form-control" id="updateCookingTime" name="cooking_time" min="1" required>
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
                                            <input type="file" class="form-control" name="thumbnail" accept="image/*">
                                            <small class="text-muted">Leave empty to keep current thumbnail</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">New Additional Images (optional, max 4)</label>
                                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                            <small class="text-muted">Select new images to replace ALL current additional images</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Steps -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Steps</label>
                                    <div id="updateStepsContainer">
                                        <!-- Steps will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addUpdateStep()">Add Step</button>
                                </div>

                                <!-- Alats -->
                                <div class="mb-3">
                                    <label class="form-label">Cooking Tools (Alat)</label>
                                    <div id="updateAlatsContainer">
                                        <!-- Alats will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addUpdateAlat()">Add Tool</button>
                                </div>

                                <!-- Bahans -->
                                <div class="mb-3">
                                    <label class="form-label">Ingredients (Bahan)</label>
                                    <div id="updateBahansContainer">
                                        <!-- Bahans will be populated dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addUpdateBahan()">Add Ingredient</button>
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
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="markAllAsRead()">Mark All Read</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="loadNotifications()">Refresh</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="unreadOnlyFilter" onchange="loadNotifications()">
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
                                    <input type="text" class="form-control" id="searchMyRecipes" placeholder="Search my recipes...">
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Cooking Tools (Alat)</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-3" onclick="loadAlats()">Load Alats</button>
                                    <div id="alatsList"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Ingredients (Bahan)</h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mb-3" onclick="loadBahans()">Load Bahans</button>
                                    <div id="bahansList"></div>
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

        // Base API URL
        const API_BASE = 'http://localhost/luwe/public/api';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved user data
            const savedUser = localStorage.getItem('current_user');
            if (savedUser) {
                currentUser = JSON.parse(savedUser);
            }
            
            updateAuthStatus();
            loadMasterData();
            
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
                
                const response = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
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
                displayResponse({error: error.message});
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
                
                const response = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: passwordConfirm
                    })
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
                alert('Error loading recipe: ' + error.message);
            }
        }

        // Populate update form
        function populateUpdateForm(recipe) {
            document.getElementById('updateRecipeId').value = recipe.id;
            document.getElementById('updateTitle').value = recipe.title;
            document.getElementById('updateCookingTime').value = recipe.cooking_time;
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
                        <div class="col-md-6">
                            <select class="form-select" name="bahans[${index}][id]">
                                <option value="">Select ingredient...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="bahans[${index}][amount]" value="${bahan.pivot.amount || ''}" placeholder="Amount (e.g., 2 cups, 500g)">
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
                    <div class="col-md-6">
                        <select class="form-select" name="bahans[${updateBahanCounter}][id]">
                            <option value="">Select ingredient...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="bahans[${updateBahanCounter}][amount]" placeholder="Amount (e.g., 2 cups, 500g)">
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

        // Load recipes
        async function loadRecipes() {
            try {
                const response = await fetch(`${API_BASE}/recipes`);
                const data = await response.json();
                displayResponse(data);
                
                if (data.success) {
                    displayRecipesList(data.data.data);
                }
            } catch (error) {
                displayResponse({error: error.message});
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

        // View recipe detail
        async function viewRecipe(id) {
            try {
                const headers = {};
                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }
                
                const response = await fetch(`${API_BASE}/recipes/${id}`, { headers });
                const data = await response.json();
                displayResponse(data);
            } catch (error) {
                displayResponse({error: error.message});
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
                            if (confirm('Recipe favorited! Would you like to check notifications to see if the recipe owner gets notified?')) {
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
                displayResponse({error: error.message});
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
                        if (confirm('Recipe favorited! Would you like to check notifications to see if the recipe owner gets notified?')) {
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
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
                }
            } catch (error) {
                console.error('Error loading bahans:', error);
            }
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
                    <div class="col-md-6">
                        <select class="form-select" name="bahans[${bahanCounter}][id]">
                            <option value="">Select ingredient...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="bahans[${bahanCounter}][amount]" placeholder="Amount (e.g., 2 cups, 500g)">
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
                displayResponse({error: error.message});
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
                displayResponse({error: error.message});
                alert('Error updating recipe: ' + error.message);
            }
        });

        // Search recipes
        document.getElementById('searchRecipes').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            if (searchTerm.length > 2 || searchTerm.length === 0) {
                searchRecipes(searchTerm);
            }
        });

        // Search recipes function
        async function searchRecipes(search = '') {
            try {
                const url = search ? `${API_BASE}/recipes?search=${encodeURIComponent(search)}` : `${API_BASE}/recipes`;
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success) {
                    displayRecipesList(data.data.data);
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        }

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
                displayResponse({error: error.message});
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
                const url = search ? `${API_BASE}/my-recipes?search=${encodeURIComponent(search)}` : `${API_BASE}/my-recipes`;
                
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
                displayResponse({error: error.message});
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
    </script>
</body>
</html>
