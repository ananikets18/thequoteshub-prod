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

// Process the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followed_user_id'])) {
    // Note: CSRF validation is handled in index.php middleware before this file is included
    
    $followerId = intval($_SESSION['user_id']);
    $followedUserId = intval($_POST['followed_user_id']);  // Use followed_user_id

    $controller = new FollowController($conn);
    $response = $controller->toggleFollow($followerId, $followedUserId);

    echo json_encode($response);
    exit;
}

// Invalid request
echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;
?>
