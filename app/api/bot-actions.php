<?php
/**
 * Bot API Endpoint - Like/Save quotes without CSRF validation
 * This endpoint is specifically for the bot to avoid CSRF issues
 * Security: Uses API key authentication instead of CSRF tokens
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config/database.php';

// Check if request has valid API key
$apiKey = $_SERVER['HTTP_X_BOT_API_KEY'] ?? $_POST['api_key'] ?? null;
$validApiKey = getenv('BOT_API_KEY') ?: 'bot_7k9m2n4p6q8r1s3t5v7w9x0y2z4a6b8c'; // Secure random key

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
// Direct database query for likes (bypassing LikeModel dependencies)
        $checkStmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND quote_id = ?");
        $checkStmt->bind_param("ii", $userId, $quoteId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $isLiked = $result->num_rows > 0;
        $checkStmt->close();
        
        if ($isLiked) {
            // Remove like
            $deleteStmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND quote_id = ?");
            $deleteStmt->bind_param("ii", $userId, $quoteId);
            $deleteStmt->execute();
            $deleteStmt->close();
            
            // Get new count
            $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM likes WHERE quote_id = ?");
            $countStmt->bind_param("i", $quoteId);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $likeCount = $countResult->fetch_assoc()['count'];
            $countStmt->close();
            
            echo json_encode(['success' => true, 'liked' => false, 'like_count' => $likeCount]);
        } else {
            // Add like
            $insertStmt = $conn->prepare("INSERT INTO likes (user_id, quote_id) VALUES (?, ?)");
            $insertStmt->bind_param("ii", $userId, $quoteId);
            $insertStmt->execute();
            $insertStmt->close();
            
            // Get new count
            $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM likes WHERE quote_id = ?");
            $countStmt->bind_param("i", $quoteId);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $likeCount = $countResult->fetch_assoc()['count'];
            $countStmt->close();
            
            echo json_encode(['success' => true, 'liked' => true, 'like_count' => $likeCount]);
        }
    } elseif ($action === 'save') {
        // Direct database query for saves (table name is 'saves' not 'saved_quotes')
        $checkStmt = $conn->prepare("SELECT id FROM saves WHERE user_id = ? AND quote_id = ?");
        $checkStmt->bind_param("ii", $userId, $quoteId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $isSaved = $result->num_rows > 0;
        $checkStmt->close();
        
        if ($isSaved) {
            $deleteStmt = $conn->prepare("DELETE FROM saves WHERE user_id = ? AND quote_id = ?");
            $deleteStmt->bind_param("ii", $userId, $quoteId);
            $deleteStmt->execute();
            $deleteStmt->close();
            echo json_encode(['success' => true, 'saved' => false]);
        } else {
            $insertStmt = $conn->prepare("INSERT INTO saves (user_id, quote_id) VALUES (?, ?)");
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
