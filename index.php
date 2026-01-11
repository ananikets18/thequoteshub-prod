<?php
/**
 * Main Router - The Quotes Hub
 * 
 * This file handles all incoming requests and routes them to appropriate controllers.
 * It implements a simple front controller pattern with middleware-like authentication checks.
 * 
 * @package QuotesHub
 * @version 2.0
 */

// ============================================
// INITIALIZATION
// ===========================================


// Configure secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// Enable secure flag for HTTPS connections
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Start session
session_start();

// Initialize centralized error handler
require_once __DIR__ . '/config/ErrorHandler.php';
ErrorHandler::init();

// Error reporting (controlled by APP_DEBUG in env.php)
if (defined('APP_DEBUG') && APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ============================================
// DEPENDENCIES
// ============================================

// Load configuration (includes env.php and database.php)
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/utilis.php';

// Controllers
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/AuthorController.php';
require_once __DIR__ . '/app/controllers/BlogController.php';
require_once __DIR__ . '/app/controllers/CategoryController.php';
require_once __DIR__ . '/app/controllers/contentController.php';
require_once __DIR__ . '/app/controllers/FollowController.php';
require_once __DIR__ . '/app/controllers/NotificationController.php';
require_once __DIR__ . '/app/controllers/QuoteController.php';
require_once __DIR__ . '/app/controllers/SearchController.php';
require_once __DIR__ . '/app/controllers/UserController.php';

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Validate CSRF token for POST/PUT/DELETE requests
 * Automatically validates and logs failures
 * @return bool
 */
function validateCsrfForRequest() {
    // Skip CSRF validation for GET requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return true;
    }
    
    // Get token from POST data or headers
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    
    if (!$token || !validateCsrfToken($token)) {
        ErrorHandler::log('CSRF validation failed', [
            'uri' => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD'],
            'user_id' => $_SESSION['user_id'] ?? 'guest'
        ]);
        return false;
    }
    
    return true;
}

/**
 * Check if user is authenticated
 * @return bool
 */
function isUserAuthenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is authenticated
 * @return bool
 */
function isAdminAuthenticated() {
    return isset($_SESSION['admin_id']);
}

/**
 * Redirect to login if user is not authenticated
 * @param string $redirectUrl
 */
function requireAuth($redirectUrl = '/login') {
    if (!isUserAuthenticated()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 * Redirect to admin login if admin is not authenticated
 */
function requireAdminAuth() {
    if (!isAdminAuthenticated()) {
        header("Location: /admin/login");
        exit();
    }
}

/**
 * Redirect if user is already authenticated
 * @param string $redirectUrl
 */
function redirectIfAuthenticated($redirectUrl = '/dashboard') {
    if (isUserAuthenticated()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 * Redirect if admin is already authenticated
 */
function redirectIfAdminAuthenticated() {
    if (isAdminAuthenticated()) {
        header("Location: /admin/dashboard");
        exit();
    }
}

/**
 * Send JSON error response
 * @param string $message
 * @param int $code
 */
function jsonError($message, $code = 400) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $message]);
    exit();
}

/**
 * Include a static page
 * @param string $page
 */
function includePage($page) {
    $filePath = __DIR__ . "/app/views/pages/{$page}.php";
    if (file_exists($filePath)) {
        include $filePath;
    } else {
        show404();
    }
}

/**
 * Show 404 error page
 */
function show404() {
    header("HTTP/1.0 404 Not Found");
    include __DIR__ . '/app/views/pages/404-page.php';
    exit();
}

// ============================================
// ROUTING
// ============================================

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// CSRF Protection: Validate CSRF token for all POST requests
if ($method !== 'GET' && $method !== 'OPTIONS') {
    if (!validateCsrfForRequest()) {
        if (strpos($path, '/api/') === 0 || 
            (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
             strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            // AJAX request - return JSON error
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid security token. Please refresh the page and try again.',
                'correlation_id' => ErrorHandler::getCorrelationId()
            ]);
            exit;
        } else {
            // Regular form - show error page
            http_response_code(403);
            echo "<!DOCTYPE html><html><head><title>Security Error</title></head><body>";
            echo "<h1>403 - Forbidden</h1>";
            echo "<p>Invalid security token. Please <a href='/'>go back</a> and try again.</p>";
            echo "</body></html>";
            exit;
        }
    }
}

// Normalize path for subdirectory installations
// Remove /public_html prefix if present (for local development)
$basePath = '/public_html';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
    if (empty($path)) {
        $path = '/';
    }
}

// ============================================
// API ROUTES
// ============================================

if (strpos($path, '/api/') === 0) {
    switch ($path) {
        case '/api/follow':
            require_once __DIR__ . '/app/api/follow.php';
            exit;
        
        case '/api/follow-status':
            require_once __DIR__ . '/app/api/follow-status.php';
            exit;
        
        case '/api/follow-counts':
            require_once __DIR__ . '/app/api/follow-counts.php';
            exit;
        
        default:
            jsonError('API endpoint not found', 404);
    }
}

// ============================================
// PUBLIC ROUTES
// ============================================

// Home & Quotes
if ($path === '/' || $path === '/quotes') {
    $controller = new QuoteController($conn);
    $controller->index();
    exit;
}

// Single Quote View
elseif (preg_match('/^\/quote\/(\d+)$/', $path, $matches)) {
    $id = (int)$matches[1];
    $controller = new QuoteController($conn);
    $controller->view($id);
    exit;
}

// Quote Like (AJAX)
elseif (preg_match('/^\/quote\/(\d+)\/like$/', $path, $matches)) {
    requireAuth();
    $id = (int)$matches[1];
    $controller = new QuoteController($conn);
    $controller->toggleLike($id);
    exit;
}

// Quote Save (AJAX)
elseif (preg_match('/^\/quote\/(\d+)\/save$/', $path, $matches)) {
    requireAuth();
    $id = (int)$matches[1];
    $controller = new QuoteController($conn);
    $controller->toggleSave($id);
    exit;
}

// Customize Quote
elseif (preg_match('/^\/customize_quote\/(\d+)$/', $path, $matches)) {
    $id = (int)$matches[1];
    $controller = new contentController($conn);
    $controller->customizeQuote($id);
    exit;
}

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Register
elseif ($path === '/register') {
    redirectIfAuthenticated();
    $controller = new UserController($conn);
    
    if ($method === 'POST') {
        if (isset($_POST['google_token'])) {
            $controller->registerWithGoogle($_POST['name'], $_POST['email']);
        } else {
            $controller->register();
        }
    } else {
        $controller->showRegistrationForm();
    }
    exit;
}

// Login
elseif ($path === '/login') {
    redirectIfAuthenticated();
    $controller = new UserController($conn);
    $controller->login();
    exit;
}

// Logout
elseif ($path === '/logout') {
    requireAuth();
    $controller = new UserController($conn);
    $controller->logout();
    exit;
}

// Change Password
elseif ($path === '/changePassword') {
    requireAuth();
    $controller = new UserController($conn);
    $controller->changePassword();
    exit;
}

// Forgot Password
elseif ($path === '/forget-password') {
    includePage('users/forget-password');
    exit;
}

// ============================================
// USER ROUTES (Authenticated)
// ============================================

// Dashboard
elseif ($path === '/dashboard') {
    requireAuth();
    $controller = new QuoteController($conn);
    $controller->dashboard();
    exit;
}

// Profile
elseif ($path === '/profile') {
    requireAuth();
    $controller = new UserController($conn);
    $controller->profile();
    exit;
}

// Create Quote
elseif ($path === '/create-quote') {
    $controller = new QuoteController($conn);
    $controller->createQuote();
    exit;
}

// Edit Quote
elseif (preg_match('/^\/edit-quote\/(\d+)$/', $path, $matches)) {
    requireAuth();
    $id = (int)$matches[1];
    $controller = new QuoteController($conn);
    $controller->editQuote($id);
    exit;
}

// Saved Quotes
elseif ($path === '/saved-quotes') {
    $controller = new UserController($conn);
    $controller->showSavedQuotes();
    exit;
}

// Liked Quotes
elseif ($path === '/liked-quotes') {
    $controller = new UserController($conn);
    $controller->showLikedQuotes();
    exit;
}

// Notifications
elseif ($path === '/notifications') {
    requireAuth();
    $controller = new NotificationController($conn);
    $controller->showUnreadNotifications($_SESSION['user_id']);
    exit;
}

// Mark All Notifications as Read
elseif ($path === '/mark-all-as-read') {
    requireAuth();
    $controller = new NotificationController($conn);
    $type = $_POST['notification_type'] ?? 'all';
    
    if ($type === 'follows') {
        $controller->markAllFollowNotificationsAsRead($_SESSION['user_id']);
    } elseif ($type === 'all') {
        $controller->markAllAsRead($_SESSION['user_id']);
        $controller->markAllFollowNotificationsAsRead($_SESSION['user_id']);
    }
    
    header("Location: /notifications?status=success");
    exit;
}

// ============================================
// FOLLOW SYSTEM ROUTES (AJAX)
// ============================================

// Get Following Feed
elseif ($path === '/following') {
    $controller = new FollowController($conn);
    $controller->getFollowing();
    exit;
}

// Get Followers List (AJAX)
elseif ($path === '/get_followers_list') {
    if (!isset($_GET['user_id'])) {
        jsonError('User ID is missing');
    }
    $controller = new FollowController($conn);
    $controller->getFollowersList($_GET['user_id']);
    exit;
}

// Get Following List (AJAX)
elseif ($path === '/get_following_list') {
    if (!isset($_GET['user_id'])) {
        jsonError('User ID is missing');
    }
    $controller = new FollowController($conn);
    $controller->getFollowingList($_GET['user_id']);
    exit;
}

// ============================================
// CATEGORY ROUTES
// ============================================

// All Categories
elseif ($path === '/categories') {
    $controller = new CategoryController($conn);
    $controller->index();
    exit;
}

// Category View
elseif (preg_match('/^\/category\/([^\/]+)$/', $path, $matches)) {
    $categoryName = htmlspecialchars(urldecode($matches[1]));
    $controller = new CategoryController($conn);
    $controller->viewCategory($categoryName);
    exit;
}

// ============================================
// AUTHOR ROUTES
// ============================================

// All Authors
elseif ($path === '/authors') {
    $controller = new QuoteController($conn);
    $controller->listAuthors();
    exit;
}

// Author Quotes
elseif (preg_match('/^\/authors\/([^\/]+)$/', $path, $matches)) {
    $authorName = htmlspecialchars(urldecode($matches[1]));
    $controller = new QuoteController($conn);
    $controller->viewAuthorQuotes($authorName);
    exit;
}

// Update Authors (Admin Tool)
elseif ($path === '/update-authors') {
    $controller = new AuthorController($conn);
    $controller->listUnupdatedAuthors();
    exit;
}

// Create Author
elseif (preg_match('#^/create-author/([\w\+]+)$#', $path, $matches)) {
    $authorName = urldecode($matches[1]);
    $controller = new AuthorController($conn);
    $controller->addAuthor($authorName);
    exit;
}

// Save Author
elseif ($path === '/save-author') {
    $controller = new AuthorController($conn);
    $controller->saveNewAuthor();
    exit;
}

// ============================================
// BLOG ROUTES
// ============================================

// All Blogs
elseif ($path === '/blogs') {
    $controller = new BlogController($conn);
    $controller->index();
    exit;
}

// View Blog
elseif (preg_match('/^\/view\/(\d+)$/', $path, $matches)) {
    $id = (int)$matches[1];
    $controller = new BlogController($conn);
    $controller->view($id);
    exit;
}

// ============================================
// ADMIN ROUTES
// ============================================

// Admin Register
elseif ($path === '/admin/register') {
    redirectIfAdminAuthenticated();
    $controller = new AdminController($conn);
    $controller->register();
    exit;
}

// Admin Login
elseif ($path === '/admin/login') {
    redirectIfAdminAuthenticated();
    $controller = new AdminController($conn);
    $controller->login();
    exit;
}

// Admin Logout
elseif ($path === '/admin/logout') {
    $controller = new AdminController($conn);
    $controller->logout();
    exit;
}

// Admin Dashboard
elseif ($path === '/admin/dashboard') {
    requireAdminAuth();
    $controller = new AdminController($conn);
    $controller->dashboard();
    exit;
}

// Admin Analytics
elseif ($path === '/admin/analytics') {
    requireAdminAuth();
    $controller = new AdminController($conn);
    $controller->analytics();
    exit;
}

// Admin All Quotes
elseif ($path === '/admin/allquotes') {
    requireAdminAuth();
    $controller = new AdminController($conn);
    $controller->listAllQuotes();
    exit;
}

// Admin Edit Quote
elseif (preg_match('/^\/admin\/editquote\/(\d+)$/', $path, $matches)) {
    requireAdminAuth();
    $id = (int)$matches[1];
    $controller = new AdminController($conn);
    $controller->editQuoteAdmin($id);
    exit;
}

// Admin Delete Quote
elseif (preg_match('/^\/admin\/deletequote\/(\d+)$/', $path, $matches)) {
    requireAdminAuth();
    $id = (int)$matches[1];
    $controller = new AdminController($conn);
    $controller->deleteQuoteAdmin($id);
    exit;
}

// Admin Create Blog
elseif ($path === '/admin/create-blog') {
    requireAdminAuth();
    $controller = new AdminController($conn);
    $controller->createBlog();
    exit;
}

// Admin Edit Blog
elseif (preg_match('/^\/admin\/edit-blog\/(\d+)$/', $path, $matches)) {
    requireAdminAuth();
    $id = (int)$matches[1];
    $controller = new AdminController($conn);
    $controller->editBlog($id);
    exit;
}

// Admin Delete Blog
elseif (preg_match('/^\/admin\/delete-blog\/(\d+)$/', $path, $matches)) {
    requireAdminAuth();
    $id = (int)$matches[1];
    $controller = new AdminController($conn);
    $controller->deleteBlog($id);
    exit;
}

// ============================================
// SEARCH ROUTE
// ============================================

elseif ($path === '/search') {
    if (!isset($_GET['q'])) {
        jsonError('Search query is missing');
    }
    $controller = new SearchController($conn);
    header('Content-Type: application/json');
    echo json_encode($controller->search($_GET['q']));
    exit;
}

// ============================================
// STATIC PAGES
// ============================================

elseif ($path === '/about') {
    includePage('about');
    exit;
}

elseif ($path === '/guidelines') {
    includePage('guidelines');
    exit;
}

elseif ($path === '/terms') {
    includePage('terms');
    exit;
}

elseif ($path === '/privacy') {
    includePage('privacy');
    exit;
}

elseif ($path === '/disclaimer') {
    includePage('disclaimer');
    exit;
}

elseif ($path === '/contact') {
    includePage('contact');
    exit;
}

elseif ($path === '/feedback') {
    includePage('feedback');
    exit;
}

elseif ($path === '/badges') {
    includePage('badges');
    exit;
}

elseif ($path === '/settings') {
    includePage('settings');
    exit;
}

elseif ($path === '/top-users') {
    $controller = new UserController($conn);
    $controller->showTopUsers();
    exit;
}

// ============================================
// USER PROFILE ROUTE (Catch-all for usernames)
// Must be last before 404
// ============================================

elseif (preg_match('/^\/([^\/]+)$/', $path, $matches)) {
    $username = htmlspecialchars($matches[1]);
    $controller = new UserController($conn);
    $controller->showUserProfile($username);
    exit;
}

// ============================================
// 404 NOT FOUND
// ============================================

else {
    show404();
}
