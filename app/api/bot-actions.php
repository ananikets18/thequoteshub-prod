<?php
/**
 * Bot API Endpoint - Like/Save quotes without CSRF validation
 * This endpoint is specifically for the bot to avoid CSRF issues
 * Security: Uses API key authentication instead of CSRF tokens
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/LikeModel.php';

// Check if request has valid API key
$apiKey = $_SERVER['HTTP_X_BOT_API_KEY'] ?? $_POST['api_key'] ?? null;
$validApiKey = getenv('BOT_API_KEY') ?: 'your-secret-bot-api-key-here'; // Change this!

if ($apiKey !== $validApiKey) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid API key']);
    exit;
}

// Get parameters
$action = $_POST['action'] ?? null; // 'like' or 'save'
$quoteId = (int)($_POST['quote_id'] ?? 0);
$userId = (int)($_POST['user_id'] ?? 0);

if (!$action || !$quoteId || !$userId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit;
}

header('Content-Type: application/json');

try {
    if ($action === 'like') {
        $likeModel = new LikeModel($conn);
        $isLiked = $likeModel->isLiked($userId, $quoteId);
        
        if ($isLiked) {
            $likeModel->removeLike($userId, $quoteId);
            $likeCount = $likeModel->getLikeCount($quoteId);
            echo json_encode(['success' => true, 'liked' => false, 'like_count' => $likeCount]);
        } else {
            $likeModel->addLike($userId, $quoteId);
            $likeCount = $likeModel->getLikeCount($quoteId);
            echo json_encode(['success' => true, 'liked' => true, 'like_count' => $likeCount]);
        }
    } elseif ($action === 'save') {
        // Direct database query for saves (in case SaveModel doesn't exist)
        $checkStmt = $conn->prepare("SELECT id FROM saved_quotes WHERE user_id = ? AND quote_id = ?");
        $checkStmt->bind_param("ii", $userId, $quoteId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $isSaved = $result->num_rows > 0;
        $checkStmt->close();
        
        if ($isSaved) {
            $deleteStmt = $conn->prepare("DELETE FROM saved_quotes WHERE user_id = ? AND quote_id = ?");
            $deleteStmt->bind_param("ii", $userId, $quoteId);
            $deleteStmt->execute();
            $deleteStmt->close();
            echo json_encode(['success' => true, 'saved' => false]);
        } else {
            $insertStmt = $conn->prepare("INSERT INTO saved_quotes (user_id, quote_id) VALUES (?, ?)");
            $insertStmt->bind_param("ii", $userId, $quoteId);
            $insertStmt->execute();
            $insertStmt->close();
            echo json_encode(['success' => true, 'saved' => true]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
