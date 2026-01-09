<?php
/**
 * Database Configuration
 * 
 * Establishes database connection using environment variables
 * 
 * @package QuotesHub
 * @version 2.0
 */

// Load environment configuration
require_once __DIR__ . '/env.php';

// Database connection parameters from environment
$server = DB_HOST;
$user = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

// Create connection
$conn = mysqli_connect($server, $user, $password, $dbname);

// Check connection
if (!$conn) {
    if (APP_DEBUG) {
        die("Database Connection Failed: " . mysqli_connect_error());
    } else {
        die("Database connection error. Please contact support.");
    }
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Optional: Display success message in debug mode
if (APP_DEBUG && isLocal()) {
    // Uncomment for debugging
    // echo "✅ Database connected successfully to: " . DB_NAME . "<br>";
}