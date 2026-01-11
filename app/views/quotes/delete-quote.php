<?php
session_start();
include __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/utilis.php';

// Set the response header to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate CSRF token
if (!isset($data['csrf_token']) || !validateCsrfToken($data['csrf_token'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid security token.']);
    exit;
}

// Validate input
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid quote ID.']);
    exit;
}

$quoteId = intval($data['id']);

// Prepare the delete query
$stmt = $conn->prepare("DELETE FROM quotes WHERE id = ? AND user_id = ?");
if (!$stmt) {
    error_log("Error preparing statement: " . $conn->error);
    echo json_encode(['success' => false, 'error' => 'Could not prepare statement.']);
    exit;
}

$stmt->bind_param("ii", $quoteId, $_SESSION['user_id']);
$stmt->execute();

// Check if the quote was successfully deleted
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not delete quote.']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
