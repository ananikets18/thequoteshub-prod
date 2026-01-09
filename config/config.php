<?php
/**
 * Application Configuration
 * 
 * Central configuration file for application-wide settings
 * Uses environment variables for flexibility
 * 
 * @package QuotesHub
 * @version 1.0
 */

// Load environment configuration
require_once __DIR__ . '/env.php';

// ============================================
// APPLICATION SETTINGS
// ============================================

// Application Name
define('SITE_NAME', APP_NAME);

// Base URL (no trailing slash)
define('BASE_URL', rtrim(APP_URL, '/'));

// ============================================
// PATH CONSTANTS
// ============================================

// Asset paths (relative to BASE_URL)
define('CSS_PATH', BASE_URL . '/public/assets/css/');
define('JS_PATH', BASE_URL . '/public/assets/js/');
define('IMAGE_PATH', BASE_URL . '/public/uploads/images/');
define('UPLOAD_PATH', BASE_URL . '/public/uploads/');

// Server paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// ============================================
// UPLOAD SETTINGS
// ============================================

// Upload directories
define('UPLOAD_DIR', PUBLIC_PATH . '/uploads/');
define('USER_UPLOAD_DIR', UPLOAD_DIR . 'users/');
define('QUOTE_UPLOAD_DIR', UPLOAD_DIR . 'quotes/');
define('AUTHOR_UPLOAD_DIR', UPLOAD_DIR . 'authors_images/');
define('BADGE_UPLOAD_DIR', UPLOAD_DIR . 'badges/');

// Upload limits
define('MAX_UPLOAD_SIZE', UPLOAD_MAX_SIZE); // bytes
define('MAX_UPLOAD_SIZE_MB', UPLOAD_MAX_SIZE / 1024 / 1024); // MB

// Allowed file types
define('ALLOWED_IMAGES', explode(',', ALLOWED_IMAGE_TYPES));

// ============================================
// PAGINATION SETTINGS
// ============================================

define('QUOTES_PER_PAGE', 20);
define('USERS_PER_PAGE', 20);
define('BLOGS_PER_PAGE', 10);

// ============================================
// SESSION SETTINGS
// ============================================

define('SESSION_NAME', 'quoteshub_session');
define('SESSION_TIMEOUT', SESSION_LIFETIME);

// ============================================
// SECURITY SETTINGS
// ============================================

// Password requirements
define('MIN_PASSWORD_LENGTH', 8);
define('MAX_PASSWORD_LENGTH', 255);

// Username requirements
define('MIN_USERNAME_LENGTH', 3);
define('MAX_USERNAME_LENGTH', 30);

// ============================================
// FEATURE FLAGS
// ============================================

define('ENABLE_REGISTRATION', true);
define('ENABLE_GOOGLE_AUTH', true);
define('ENABLE_EMAIL_VERIFICATION', false);
define('ENABLE_COMMENTS', false);

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get base URL with optional path
 * @param string $path
 * @return string
 */
function baseUrl($path = '') {
    return BASE_URL . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Get asset URL
 * @param string $type (css, js, image)
 * @param string $file
 * @return string
 */
function assetUrl($type, $file) {
    $paths = [
        'css' => CSS_PATH,
        'js' => JS_PATH,
        'image' => IMAGE_PATH,
    ];
    
    return ($paths[$type] ?? UPLOAD_PATH) . ltrim($file, '/');
}

/**
 * Get upload URL
 * @param string $type (user, quote, author, badge)
 * @param string $file
 * @return string
 */
function uploadUrl($type, $file) {
    $paths = [
        'user' => USER_UPLOAD_DIR,
        'quote' => QUOTE_UPLOAD_DIR,
        'author' => AUTHOR_UPLOAD_DIR,
        'badge' => BADGE_UPLOAD_DIR,
    ];
    
    $basePath = $paths[$type] ?? UPLOAD_DIR;
    return str_replace(PUBLIC_PATH, BASE_URL . '/public', $basePath) . ltrim($file, '/');
}

/**
 * Check if feature is enabled
 * @param string $feature
 * @return bool
 */
function isFeatureEnabled($feature) {
    $constant = 'ENABLE_' . strtoupper($feature);
    return defined($constant) && constant($constant) === true;
}
