@echo off
echo ========================================
echo WordPress Restaurant Site Setup
echo ========================================
echo.

echo Stopping any existing containers...
docker-compose down

echo.
echo Removing old containers and volumes...
docker-compose down -v

echo.
echo Building and starting containers...
docker-compose up -d --build

echo.
echo Waiting for services to start...
timeout /t 30 /nobreak

echo.
echo Checking container status...
docker-compose ps

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Your WordPress site should be available at:
echo http://localhost:8080
echo.
echo phpMyAdmin is available at:
echo http://localhost:8081
echo.
echo MailHog is available at:
echo http://localhost:8025
echo.
echo Database credentials:
echo Host: localhost:3306
echo Database: restaurant_db
echo Username: wordpress
echo Password: secure_password_2024
echo.
echo ========================================
echo Next Steps:
echo 1. Go to http://localhost:8080
echo 2. Complete WordPress installation
echo 3. Activate the Restaurant Pro theme
echo 4. Configure your site settings
echo ========================================
echo.
pause
