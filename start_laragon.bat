@echo off
echo === Starting Laragon Services ===
echo.

REM Try common Laragon installation paths
if exist "C:\laragon\laragon.exe" (
    echo Found Laragon at C:\laragon\laragon.exe
    echo Starting Laragon...
    "C:\laragon\laragon.exe"
    goto :done
)

if exist "C:\Program Files\Laragon\laragon.exe" (
    echo Found Laragon at C:\Program Files\Laragon\laragon.exe
    echo Starting Laragon...
    "C:\Program Files\Laragon\laragon.exe"
    goto :done
)

if exist "C:\Program Files (x86)\Laragon\laragon.exe" (
    echo Found Laragon at C:\Program Files (x86)\Laragon\laragon.exe  
    echo Starting Laragon...
    "C:\Program Files (x86)\Laragon\laragon.exe"
    goto :done
)

echo Laragon not found in common installation paths!
echo Please start Laragon manually from:
echo - Start Menu
echo - Desktop shortcut
echo - Or locate laragon.exe and run it

:done
echo.
echo After starting Laragon:
echo 1. Make sure both Apache and MySQL services are started (green)
echo 2. Then run: powershell -ExecutionPolicy Bypass -File fix_mysql_laragon.ps1
echo.
pause
