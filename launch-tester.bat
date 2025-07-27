@echo off
echo ========================================
echo    MOORA API Tester Launcher
echo ========================================
echo.
echo Opening MOORA API Tester in browser...
echo.

REM Check if file exists
if exist "moora-tester.html" (
    echo ✓ Found moora-tester.html
    echo.
    echo Opening in default browser...
    start "" "moora-tester.html"
    echo.
    echo ✓ Browser opened successfully!
    echo.
    echo Instructions:
    echo 1. Wait for ingredients to load
    echo 2. Select ingredients using checkboxes or quick buttons
    echo 3. Click "Test MOORA API" button
    echo 4. Check JSON response in right panel
    echo.
    echo For Flutter integration, copy the JSON response
    echo and use it as reference for your HTTP client.
    echo.
) else (
    echo ✗ Error: moora-tester.html not found!
    echo.
    echo Please make sure you're running this from the
    echo correct directory containing moora-tester.html
    echo.
)

echo Press any key to exit...
pause >nul
