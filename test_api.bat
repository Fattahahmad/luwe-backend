@echo off
echo ====================================
echo Testing Luwe API Endpoints
echo ====================================
echo.

echo 1. Testing REGISTER endpoint
echo POST /api/register
curl -X POST http://127.0.0.1:8000/api/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"Test User API\",\"email\":\"testapi@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"

echo.
echo.

echo 2. Testing LOGIN endpoint
echo POST /api/login
curl -X POST http://127.0.0.1:8000/api/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"testapi@example.com\",\"password\":\"password123\"}" ^
  -o login_response.json

echo.
echo.

echo 3. Extract token from login response
for /f "tokens=2 delims=:" %%a in ('type login_response.json ^| findstr "token"') do (
  set TOKEN=%%a
)
set TOKEN=%TOKEN:"=%
set TOKEN=%TOKEN:,=%
set TOKEN=%TOKEN: =%

echo Token: %TOKEN%
echo.

echo 4. Testing PROFILE endpoint (with token)
echo GET /api/profile
curl -X GET http://127.0.0.1:8000/api/profile ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %TOKEN%"

echo.
echo.

echo 5. Testing LOGOUT endpoint (with token)
echo POST /api/logout
curl -X POST http://127.0.0.1:8000/api/logout ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer %TOKEN%"

echo.
echo.

echo 6. Testing LOGIN with dummy user
echo POST /api/login (dummy user)
curl -X POST http://127.0.0.1:8000/api/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password\"}"

echo.
echo.

echo Testing completed!
del login_response.json
pause
