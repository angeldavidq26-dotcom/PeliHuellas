@echo off
cd /d "%~dp0"
echo ================================
echo   Trayendo los ultimos cambios
echo ================================
git pull origin main
echo.
echo Listo.
pause
