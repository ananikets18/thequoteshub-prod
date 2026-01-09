<?php  
// session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/FollowController.php';

// Set the response to JSON
header('Content-Type: application/json');

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Check if user_id is provided in the query string (change 'followed_user_id' to 'user_id')
if (isset($_GET['user_id'])) {  // Change this line
    $userId = (int) $_GET['user_id'];  // Use user_id instead of followed_user_id
    $controller = new FollowController($conn);
    
    // Call the controller method to get the follow counts
    $controller->getFollowCounts($userId);
} else {
    echo json_encode(['success' => false, 'message' => 'No user_id provided']);
}
?>
