<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/utilis.php';
require_once __DIR__ . '/../models/NotificationModel.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check database connection
if (!isset($conn) || !$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Get parameters
$userId = $_SESSION['user_id'];
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
$type = isset($_GET['type']) ? $_GET['type'] : 'all'; // 'all', 'likes_saves', 'follows'

try {
    $notificationModel = new NotificationModel($conn);
    
    // Get notifications with pagination
    $result = $notificationModel->getAllNotifications($userId, $limit, $offset);
    
    // Filter by type if specified
    $notifications = $result['notifications'];
    if ($type === 'likes_saves') {
        $notifications = array_filter($notifications, fn($n) => in_array($n['type'], ['like', 'save']));
        $notifications = array_values($notifications); // Reindex array
    } elseif ($type === 'follows') {
        $notifications = array_filter($notifications, fn($n) => $n['type'] === 'follow');
        $notifications = array_values($notifications); // Reindex array
    }
    
    // Prepare response with HTML for each notification
    $html = '';
    $baseUrl = getBaseUrl();
    
    foreach ($notifications as $notification) {
        if ($notification['type'] === 'follow') {
            // Follow notification HTML
            $userImageFile = $notification['sender_img'];
            $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
            $imageSrc = !empty($userImageFile) && file_exists($userImagePath) 
                ? $baseUrl . 'public/uploads/users/' . $userImageFile 
                : $baseUrl . 'public/uploads/authors_images/placeholder.png';
            
            $readClass = $notification['is_read'] ? 'bg-white' : 'bg-green-50 outline outline-1 outline-green-300';
            
            $html .= '<div class="p-2 rounded-lg shadow mb-4 transition ' . $readClass . '">';
            $html .= '<div class="flex items-center">';
            $html .= '<img src="' . htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'alt="Follow Notification" class="w-10 h-10 rounded-full object-cover border mr-4" />';
            $html .= '<div class="flex-1">';
            $html .= '<a href="' . $baseUrl . decodeCleanAndRemoveTags(decodeAndCleanText($notification['user_name'])) . '" ';
            $html .= 'class="text-sm font-medium text-gray-800">';
            $html .= htmlspecialchars($notification['sender_name']) . ' started following you';
            $html .= '</a>';
            $html .= '</div></div></div>';
        } else {
            // Like/Save notification HTML
            $userImageFile = $notification['sender_img'];
            $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
            $imageSrc = !empty($userImageFile) && file_exists($userImagePath) 
                ? $baseUrl . 'public/uploads/users/' . $userImageFile 
                : $baseUrl . 'public/uploads/authors_images/placeholder.png';
            
            $readClass = $notification['is_read'] ? 'bg-white' : 
                ($notification['type'] === 'like' ? 'bg-green-100 outline outline-1 outline-green-400' : 'bg-blue-100 outline outline-2 outline-blue-500');
            
            $html .= '<div class="p-2.5 rounded-lg shadow mb-4 transition ' . $readClass . '">';
            $html .= '<div class="flex items-center">';
            $html .= '<img src="' . htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'alt="' . htmlspecialchars($notification['quote_text'], ENT_QUOTES, 'UTF-8') . '" ';
            $html .= 'class="w-12 h-12 lg:w-18 lg:h-18 rounded-full object-cover border mr-4" />';
            $html .= '<div class="flex-1">';
            $html .= '<a href="' . $baseUrl . decodeCleanAndRemoveTags(decodeAndCleanText($notification['user_name'])) . '" ';
            $html .= 'class="font-bold text-gray-800 hover:text-green-600 capitalize text-sm md:text-md">';
            $html .= htmlspecialchars($notification['sender_name']);
            $html .= '<span class="ml-2 text-sm ' . ($notification['type'] === 'like' ? 'text-blue-600' : 'text-green-600') . '">';
            $html .= '<i class="fas ' . ($notification['type'] === 'like' ? 'fa-thumbs-up' : 'fa-bookmark') . '"></i>';
            $html .= '</span></a>';
            $html .= '<p class="text-gray-700">';
            $html .= '<a href="' . $baseUrl . 'quote/' . urlencode($notification['quote_id']) . '" ';
            $html .= 'class="italic text-blue-500 hover:underline text-xs md:text-sm lg:text-md">';
            $html .= htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($notification['quote_text'])));
            $html .= '</a></p></div></div></div>';
        }
    }
    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'hasMore' => $result['hasMore'],
        'total' => $result['total']
    ]);
    
} catch (Exception $e) {
    error_log("Error loading notifications: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        'success' => false, 
        'error' => 'Failed to load notifications',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>