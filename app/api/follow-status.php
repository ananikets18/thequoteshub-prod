<?php 
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/FollowController.php';

// Set the response to JSON
header('Content-Type: application/json');

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Check if followed_user_id is provided in the query string
if (isset($_GET['followed_user_id'])) {
    $followedUserId = (int) $_GET['followed_user_id'];
    $controller = new FollowController($conn);
    
    // Call the controller method to check follow status
    $controller->getFollowStatus($_SESSION['user_id'], $followedUserId);
} else {
    echo json_encode(['success' => false, 'message' => 'No followed_user_id provided']);
}

?>
