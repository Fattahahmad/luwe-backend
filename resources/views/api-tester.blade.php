<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tester - Luwe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .test-section h3 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .response {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
        }

        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .token-display {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Luwe API Tester</h1>

        <!-- Register Test -->
        <div class="test-section">
            <h3>1. Register Test</h3>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" id="registerName" value="Test User API">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="registerEmail" value="testapi@example.com">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" id="registerPassword" value="password123">
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" id="registerPasswordConfirm" value="password123">
            </div>
            <button class="btn btn-primary" onclick="testRegister()">Test Register</button>
            <div id="registerResponse" class="response" style="display: none;"></div>
        </div>

        <!-- Login Test -->
        <div class="test-section">
            <h3>2. Login Test</h3>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="loginEmail" value="test@example.com">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" id="loginPassword" value="password">
            </div>
            <button class="btn btn-success" onclick="testLogin()">Test Login</button>
            <button class="btn btn-warning" onclick="testLoginWithNewUser()">Test Login (New User)</button>
            <div id="loginResponse" class="response" style="display: none;"></div>
            <div id="tokenDisplay" class="token-display" style="display: none;"></div>
        </div>

        <!-- Profile Test -->
        <div class="test-section">
            <h3>3. Profile Test</h3>
            <p>Requires valid token from login</p>
            <button class="btn btn-primary" onclick="testProfile()">Test Profile</button>
            <div id="profileResponse" class="response" style="display: none;"></div>
        </div>

        <!-- Update Profile Test -->
        <div class="test-section">
            <h3>4. Update Profile Test</h3>
            <p>Requires valid token from login</p>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" id="updateName" value="Updated Name">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="updateEmail" value="updated@example.com">
            </div>
            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" id="updateProfilePicture" accept="image/*">
            </div>
            <button class="btn btn-warning" onclick="testUpdateProfile()">Test Update Profile</button>
            <div id="updateProfileResponse" class="response" style="display: none;"></div>
        </div>

        <!-- Logout Test -->
        <div class="test-section">
            <h3>5. Logout Test</h3>
            <p>Requires valid token from login</p>
            <button class="btn btn-danger" onclick="testLogout()">Test Logout</button>
            <div id="logoutResponse" class="response" style="display: none;"></div>
        </div>

        <div class="test-section">
            <h3>API Information</h3>
            <p><strong>Base URL:</strong> {{ url('/api') }}</p>
            <p><strong>Current Token:</strong> <span id="currentToken">None</span></p>
        </div>
    </div>

    <script>
        let authToken = null;

        // Test Register
        async function testRegister() {
            const data = {
                name: document.getElementById('registerName').value,
                email: document.getElementById('registerEmail').value,
                password: document.getElementById('registerPassword').value,
                password_confirmation: document.getElementById('registerPasswordConfirm').value
            };

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                displayResponse('registerResponse', result, response.status);

                if (result.success && result.data.token) {
                    authToken = result.data.token;
                    updateTokenDisplay();
                }
            } catch (error) {
                displayResponse('registerResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Test Login
        async function testLogin() {
            const data = {
                email: document.getElementById('loginEmail').value,
                password: document.getElementById('loginPassword').value
            };

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                displayResponse('loginResponse', result, response.status);

                if (result.success && result.data.token) {
                    authToken = result.data.token;
                    updateTokenDisplay();
                }
            } catch (error) {
                displayResponse('loginResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Test Login with New User
        async function testLoginWithNewUser() {
            const data = {
                email: document.getElementById('registerEmail').value,
                password: document.getElementById('registerPassword').value
            };

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                displayResponse('loginResponse', result, response.status);

                if (result.success && result.data.token) {
                    authToken = result.data.token;
                    updateTokenDisplay();
                }
            } catch (error) {
                displayResponse('loginResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Test Profile
        async function testProfile() {
            if (!authToken) {
                displayResponse('profileResponse', {
                    error: 'No token available. Please login first.'
                }, 401);
                return;
            }

            try {
                const response = await fetch('/api/profile', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const result = await response.json();
                displayResponse('profileResponse', result, response.status);
            } catch (error) {
                displayResponse('profileResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Test Logout
        async function testLogout() {
            if (!authToken) {
                displayResponse('logoutResponse', {
                    error: 'No token available. Please login first.'
                }, 401);
                return;
            }

            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    }
                });

                const result = await response.json();
                displayResponse('logoutResponse', result, response.status);

                if (result.success) {
                    authToken = null;
                    updateTokenDisplay();
                }
            } catch (error) {
                displayResponse('logoutResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Test Update Profile
        async function testUpdateProfile() {
            if (!authToken) {
                displayResponse('updateProfileResponse', {
                    error: 'No token available. Please login first.'
                }, 401);
                return;
            }

            const formData = new FormData();

            const name = document.getElementById('updateName').value;
            const email = document.getElementById('updateEmail').value;
            const profilePicture = document.getElementById('updateProfilePicture').files[0];

            if (name) formData.append('name', name);
            if (email) formData.append('email', email);
            if (profilePicture) formData.append('profile_picture', profilePicture);

            try {
                const response = await fetch('/api/profile', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: formData
                });

                const result = await response.json();
                displayResponse('updateProfileResponse', result, response.status);
            } catch (error) {
                displayResponse('updateProfileResponse', {
                    error: error.message
                }, 500);
            }
        }

        // Display Response
        function displayResponse(elementId, data, status) {
            const element = document.getElementById(elementId);
            element.style.display = 'block';
            element.textContent = `Status: ${status}\n\n${JSON.stringify(data, null, 2)}`;

            if (status >= 200 && status < 300) {
                element.className = 'response success';
            } else {
                element.className = 'response error';
            }
        }

        // Update Token Display
        function updateTokenDisplay() {
            document.getElementById('currentToken').textContent = authToken ? authToken.substring(0, 50) + '...' : 'None';

            if (authToken) {
                const tokenElement = document.getElementById('tokenDisplay');
                tokenElement.style.display = 'block';
                tokenElement.innerHTML = `<strong>Token:</strong> ${authToken}`;
            } else {
                document.getElementById('tokenDisplay').style.display = 'none';
            }
        }
    </script>
</body>

</html>
