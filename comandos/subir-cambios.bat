@echo off
cd /d "%~dp0"
echo ================================
echo   Subir mis cambios a GitHub
echo ================================
git status
echo.
set /p mensaje="Escribe en una linea que cambiaste (ej. arregle el login): "
if "%mensaje%"=="" set mensaje=cambios varios
git add .
git commit -m "%mensaje%"
git push origin main
echo.
echo Listo.
pause
