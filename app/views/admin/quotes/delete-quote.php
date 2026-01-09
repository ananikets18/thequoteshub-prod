<?php
session_start();
include __DIR__ . '/../../../../config/database.php';

// Set the response header to JSON
header('Content-Type: application/json');

// CORS headers
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow these methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow these headers

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Remove the authorization check for admin login
// Check if the admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     error_log('Admin is not logged in.');
//     echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
//     exit;
// }

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);
error_log('Received data: ' . print_r($data, true)); // Log the data

// Validate input
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid quote ID.']);
    exit;
}

$quoteId = intval($data['id']); // Get the quote ID to delete

// Prepare the delete query
$stmt = $conn->prepare("DELETE FROM quotes WHERE id = ?");
if (!$stmt) {
    error_log("Error preparing statement: " . $conn->error);
    echo json_encode(['success' => false, 'error' => 'Could not prepare statement.']);
    exit;
}

// Bind the quote ID
$stmt->bind_param("i", $quoteId);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No quote was deleted.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Execution failed: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
