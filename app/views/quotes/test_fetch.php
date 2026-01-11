<?php
// Simple test file to debug the fetch_quotes issue
session_start();
header('Content-Type: application/json');

// Test 1: Check session
$debug = [];
$debug['session_exists'] = isset($_SESSION['user_id']);
$debug['user_id'] = $_SESSION['user_id'] ?? 'NOT SET';

// Test 2: Check database connection
include __DIR__ . '/../../../config/database.php';
$debug['db_connected'] = isset($conn) && $conn !== false;

// Test 3: Check if utilities are loaded
include_once __DIR__ . '/../../../config/utilis.php';
$debug['functions_exist'] = [
    'decodeCleanAndRemoveTags' => function_exists('decodeCleanAndRemoveTags'),
    'decodeAndCleanText' => function_exists('decodeAndCleanText'),
    'formatDateTime' => function_exists('formatDateTime')
];

// Test 4: Try a simple query
if (isset($_SESSION['user_id']) && isset($conn)) {
    $id = $_SESSION['user_id'];
    $query = "SELECT COUNT(*) as count FROM quotes WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $debug['quote_count'] = $row['count'];
        $stmt->close();
    } else {
        $debug['query_error'] = $conn->error;
    }
}

echo json_encode($debug, JSON_PRETTY_PRINT);
