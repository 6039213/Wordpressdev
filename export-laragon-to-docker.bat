@echo off
echo ========================================
echo Exporting Laragon Database to Docker
echo ========================================

echo.
echo Step 1: Export Laragon database...
"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump" -uroot wordpressdev > laragon-export.sql

echo.
echo Step 2: Copy to Docker container...
docker cp laragon-export.sql schoolbord_mysql:/tmp/laragon-export.sql

echo.
echo Step 3: Drop existing Docker database...
docker compose exec db mysql -uroot -pschoolbord_root_2024 -e "DROP DATABASE IF EXISTS schoolbord_db; CREATE DATABASE schoolbord_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo.
echo Step 4: Import into Docker...
docker compose exec db mysql -uroot -pschoolbord_root_2024 schoolbord_db < laragon-export.sql

echo.
echo Step 5: Update URLs for Docker...
docker compose exec wordpress wp search-replace "http://localhost/wordpressdev" "http://localhost:8080" --all-tables --allow-root

echo.
echo Step 6: Update site URLs...
docker compose exec wordpress wp option update home "http://localhost:8080" --allow-root
docker compose exec wordpress wp option update siteurl "http://localhost:8080" --allow-root

echo.
echo Step 7: Flush rewrite rules...
docker compose exec wordpress wp rewrite flush --allow-root

echo.
echo ========================================
echo DONE! Site should now work on http://localhost:8080
echo ========================================
pause

