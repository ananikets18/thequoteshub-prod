<?php
/**
 * View Helper Functions
 * Common utility functions used across views
 */

/**
 * Get user image source with fallback to placeholder
 * 
 * @param string $userImageFile The user's image filename
 * @param string $baseUrl The base URL of the application
 * @return string The full URL to the user's image or placeholder
 */
function getUserImageSrc($userImageFile, $baseUrl) {
    if (empty($userImageFile)) {
        return $baseUrl . 'public/uploads/authors_images/placeholder.png';
    }
    
    $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
    
    return file_exists($userImagePath) 
        ? $baseUrl . 'public/uploads/users/' . $userImageFile 
        : $baseUrl . 'public/uploads/authors_images/placeholder.png';
}

/**
 * Safely output user data with decoding and sanitization
 * 
 * @param string $data The data to sanitize
 * @return string Sanitized output
 */
function safeOutput($data) {
    return decodeCleanAndRemoveTags(decodeAndCleanText($data));
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID or null
 * 
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Generate a URL using the url() helper
 * 
 * @param string $path The path to generate URL for
 * @return string The full URL
 */
function generateUrl($path) {
    return function_exists('url') ? url($path) : $path;
}

/**
 * Render a partial view
 * 
 * @param string $partialPath Path to the partial file
 * @param array $data Data to extract into the partial scope
 * @return void
 */
function renderPartial($partialPath, $data = []) {
    extract($data);
    include $partialPath;
}

/**
 * Get medal emoji based on rank
 * 
 * @param int $rank The rank (0-based index)
 * @return string Medal emoji or empty string
 */
function getMedalEmoji($rank) {
    $medals = [
        0 => '🥇', // Gold
        1 => '🥈', // Silver
        2 => '🥉'  // Bronze
    ];
    
    return $medals[$rank] ?? '';
}

/**
 * Get medal title based on rank
 * 
 * @param int $rank The rank (0-based index)
 * @return string Medal title
 */
function getMedalTitle($rank) {
    $titles = [
        0 => 'Gold Medal',
        1 => 'Silver Medal',
        2 => 'Bronze Medal'
    ];
    
    return $titles[$rank] ?? '';
}

/**
 * Format count with label
 * 
 * @param int $count The count to format
 * @param string $singular Singular form of the label
 * @param string $plural Plural form of the label
 * @return string Formatted count with label
 */
function formatCount($count, $singular, $plural = null) {
    $plural = $plural ?? $singular . 's';
    return $count . ' ' . ($count === 1 ? $singular : $plural);
}

/**
 * Truncate text to specified length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $suffix Suffix to add if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Check if current page matches given path
 * 
 * @param string $path Path to check
 * @return bool True if current page matches
 */
function isCurrentPage($path) {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    return strpos($currentPath, $path) !== false;
}

/**
 * Get active class if condition is true
 * 
 * @param bool $condition Condition to check
 * @param string $activeClass Class to return if active
 * @param string $inactiveClass Class to return if inactive
 * @return string The appropriate class
 */
function activeClass($condition, $activeClass = 'active', $inactiveClass = '') {
    return $condition ? $activeClass : $inactiveClass;
}
