<?php
/**
 * Environment Configuration Loader
 * 
 * Loads environment variables from .env file
 * Provides helper functions to access configuration
 * 
 * @package QuotesHub
 * @version 1.0
 */

// ============================================
// LOAD ENVIRONMENT VARIABLES
// ============================================

/**
 * Load environment variables from .env file
 * @return array
 */
function loadEnv() {
    $envFile = __DIR__ . '/.env';
    
    if (!file_exists($envFile)) {
        die("ERROR: .env file not found. Please copy .env.example to .env and configure it.");
    }
    
    $env = [];
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines === false) {
        die("ERROR: Failed to read .env file.");
    }
    
    foreach ($lines as $line) {
        // Skip comments and empty lines
        $line = trim($line);
        if (empty($line) || $line[0] === '#') {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes from value if present
            if (strlen($value) > 0 && ($value[0] === '"' || $value[0] === "'")) {
                $value = trim($value, '"\'');
            }
            
            $env[$key] = $value;
        }
    }
    
    return $env;
}

// Load environment variables
$env = loadEnv();

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get environment variable
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null) {
    global $env;
    return $env[$key] ?? $default;
}

/**
 * Check if app is in debug mode
 * @return bool
 */
function isDebugMode() {
    return env('APP_DEBUG', 'false') === 'true';
}

/**
 * Check if app is in production
 * @return bool
 */
function isProduction() {
    return env('APP_ENV', 'production') === 'production';
}

/**
 * Check if app is in local development
 * @return bool
 */
function isLocal() {
    return env('APP_ENV', 'production') === 'local';
}

/**
 * Get application URL
 * @param string $path
 * @return string
 */
function appUrl($path = '') {
    $url = rtrim(env('APP_URL', ''), '/');
    $path = ltrim($path, '/');
    return $path ? $url . '/' . $path : $url;
}

/**
 * Get asset URL
 * @param string $path
 * @return string
 */
function asset($path) {
    return appUrl('public/assets/' . ltrim($path, '/'));
}

/**
 * Get upload URL
 * @param string $path
 * @return string
 */
function upload($path) {
    return appUrl('public/uploads/' . ltrim($path, '/'));
}

// ============================================
// DEFINE CONSTANTS
// ============================================

// Application
define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', env('APP_DEBUG', 'false') === 'true');
define('APP_NAME', env('APP_NAME', 'The Quotes Hub'));
define('APP_URL', env('APP_URL', 'https://www.thequoteshub.in'));

// Database
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'quoteshub'));

// Session
define('SESSION_LIFETIME', (int)env('SESSION_LIFETIME', 7200));

// Uploads
define('UPLOAD_MAX_SIZE', (int)env('UPLOAD_MAX_SIZE', 5242880));
define('ALLOWED_IMAGE_TYPES', env('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,gif,webp'));

// ============================================
// ERROR HANDLING
// ============================================

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
