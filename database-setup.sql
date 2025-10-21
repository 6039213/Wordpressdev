-- WordPress Restaurant Database Setup
-- Run this in phpMyAdmin or MySQL command line

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS wordpressdev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE wordpressdev;

-- Create a user for WordPress (optional, you can use root)
-- CREATE USER 'wpuser'@'localhost' IDENTIFIED BY 'wppassword';
-- GRANT ALL PRIVILEGES ON wordpressdev.* TO 'wpuser'@'localhost';
-- FLUSH PRIVILEGES;

-- Show current databases
SHOW DATABASES;

-- Show current user
SELECT USER();

-- Show current database
SELECT DATABASE();
