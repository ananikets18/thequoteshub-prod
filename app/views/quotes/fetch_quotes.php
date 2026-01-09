<?php

session_start();
include __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/utilis.php';

// Set the response header to JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Adjust the origin as needed

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// Get user ID from the session
$id = $_SESSION['user_id'];

// Determine the filter from the URL
$filter = $_GET['filter'] ?? 'recently_created';

$query = "
    SELECT q.id, q.author_name, q.quote_text, 
           q.created_at, 
           q.updated_at, 
           COUNT(DISTINCT l.id) AS total_likes, 
           COUNT(DISTINCT s.id) AS total_saves, 
           q.view_count,
           q.is_edited
    FROM quotes q
    LEFT JOIN likes l ON q.id = l.quote_id
    LEFT JOIN saves s ON q.id = s.quote_id
    WHERE q.user_id = ?
    GROUP BY q.id
";

if ($filter == 'recently_created') {
    $query .= " ORDER BY q.created_at DESC";
} elseif ($filter == 'edited') {
    $query .= " HAVING q.is_edited = 1 ORDER BY q.updated_at DESC";
} elseif ($filter == 'most_liked') {
    $query .= " ORDER BY total_likes DESC";
} elseif ($filter == 'most_saved') {
    $query .= " ORDER BY total_saves DESC";
}



$stmt = $conn->prepare($query);
if ($stmt === false) {
    echo json_encode(['error' => 'Database prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Query execution failed: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$quotes = [];

// Generate an array of quotes
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Clean and decode data here
        $quoteId = $row['id'];
        $authorName = decodeCleanAndRemoveTags(decodeAndCleanText($row['author_name']));
        $quoteText = decodeCleanAndRemoveTags(decodeAndCleanText($row['quote_text']));
        
        // Limit to 20 characters and add ellipsis if necessary
        $authorName = strlen($authorName) > 20 ? substr($authorName, 0, 20) . '...' : $authorName;
        $quoteText = strlen($quoteText) > 20 ? substr($quoteText, 0, 20) . '...' : $quoteText;

        $isEdited = $row['is_edited'];
        $totalLikes = $row['total_likes'];
        $totalSaves = $row['total_saves'];
        $viewCount = $row['view_count'];
        $createdAt = $row['created_at'];
        $updatedAt = $row['updated_at'];

        $quotes[] = [
            'id' => $quoteId,
            'author_name' => $authorName,
            'quote_text' => $quoteText,
            'is_edited' => $isEdited,
            'total_likes' => $totalLikes,
            'total_saves' => $totalSaves,
            'view_count' => $viewCount,
            'created_at' => formatDateTime($createdAt),
            'updated_at' => $isEdited ? formatDateTime($updatedAt) : null
        ];
    }
    echo json_encode($quotes); // Return the quotes array as JSON
} else {
    echo json_encode(['message' => 'No quotes found.']);
}

$stmt->close();
$conn->close();
