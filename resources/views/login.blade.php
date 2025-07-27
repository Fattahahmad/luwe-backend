<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Luwe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .form-container {
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .api-info {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .api-info h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .api-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .api-info code {
            background-color: #e9ecef;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Login - Luwe Recipe App</h1>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <div class="error" id="passwordError"></div>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="links">
            <a href="{{ route('register') }}">Don't have account? Register</a>
            <a href="{{ route('users.index') }}">View All Users</a>
        </div>

        <div class="api-info">
            <h3>API Information for Flutter:</h3>
            <p><strong>Base URL:</strong> <code>{{ url('/api') }}</code></p>
            <p><strong>Register:</strong> <code>POST /api/register</code></p>
            <p><strong>Login:</strong> <code>POST /api/login</code></p>
            <p><strong>Logout:</strong> <code>POST /api/logout</code></p>
            <p><strong>Profile:</strong> <code>GET /api/profile</code></p>
            <p><strong>Default Password for dummy users:</strong> <code>password</code></p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear previous errors
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
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

                if (result.success) {
                    alert('Login successful!\nToken: ' + result.data.token);
                    localStorage.setItem('auth_token', result.data.token);
                    localStorage.setItem('user_data', JSON.stringify(result.data.user));
                } else {
                    if (result.errors) {
                        if (result.errors.email) {
                            document.getElementById('emailError').textContent = result.errors.email[0];
                        }
                        if (result.errors.password) {
                            document.getElementById('passwordError').textContent = result.errors.password[0];
                        }
                    } else {
                        alert('Error: ' + result.message);
                    }
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        });
    </script>
</body>

</html>
