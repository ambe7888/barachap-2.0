@echo off
echo ========================================================
echo  Compilation des applications BaraChap (Client & Pro)
echo ========================================================

echo.
echo [1/2] Compilation de l'application Client (BaraChap Client)...
cd /d "%~dp0client"
call flutter clean
call flutter pub get
call flutter build apk --release
if %errorlevel% neq 0 (
    echo [ERREUR] Echec de la compilation de l'application Client.
    pause
    exit /b %errorlevel%
)
echo [OK] Client APK généré dans client\build\app\outputs\flutter-apk\app-release.apk

echo.
echo [2/2] Compilation de l'application Pro (BaraChap Pro)...
cd /d "%~dp0pro"
call flutter clean
call flutter pub get
call flutter build apk --release
if %errorlevel% neq 0 (
    echo [ERREUR] Echec de la compilation de l'application Pro.
    pause
    exit /b %errorlevel%
)
echo [OK] Pro APK généré dans pro\build\app\outputs\flutter-apk\app-release.apk

echo.
echo ========================================================
echo  SUCCES : Les 2 applications ont été compilées avec succès !
echo ========================================================
pause
