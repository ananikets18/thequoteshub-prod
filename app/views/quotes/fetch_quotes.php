<?php

session_start();

// Set the response headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Start output buffering to catch any stray output
ob_start();

try {
    include __DIR__ . '/../../../config/database.php';
    include_once __DIR__ . '/../../../config/utilis.php';
    
    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        throw new Exception('User not authenticated');
    }

    // Get user ID from the session
    $id = $_SESSION['user_id'];

    // Filter validation - whitelist allowed filters
    $allowedFilters = ['recently_created', 'edited', 'most_liked', 'most_saved'];
    $filter = $_GET['filter'] ?? 'recently_created';
    $filter = in_array($filter, $allowedFilters) ? $filter : 'recently_created';

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
    ";

    // Add filter condition for edited quotes
    if ($filter == 'edited') {
        $query .= " AND q.is_edited = 1";
    }

    // GROUP BY all non-aggregated columns for ONLY_FULL_GROUP_BY compatibility
    $query .= " GROUP BY q.id, q.author_name, q.quote_text, q.created_at, q.updated_at, q.view_count, q.is_edited";

    // Add sorting based on filter
    if ($filter == 'recently_created') {
        $query .= " ORDER BY q.created_at DESC";
    } elseif ($filter == 'edited') {
        $query .= " ORDER BY q.updated_at DESC";
    } elseif ($filter == 'most_liked') {
        $query .= " ORDER BY total_likes DESC";
    } elseif ($filter == 'most_saved') {
        $query .= " ORDER BY total_saves DESC";
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        http_response_code(500);
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        http_response_code(500);
        throw new Exception('Query execution failed: ' . $stmt->error);
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
            
            // UTF-8 safe string truncation
            $authorName = mb_strlen($authorName) > 20 ? mb_substr($authorName, 0, 20) . '...' : $authorName;
            $quoteText = mb_strlen($quoteText) > 20 ? mb_substr($quoteText, 0, 20) . '...' : $quoteText;

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
    }

    $stmt->close();
    $conn->close();
    
    // Discard any output that was captured
    ob_end_clean();
    
    // Success response with 200 status
    http_response_code(200);
    echo json_encode($quotes);

} catch (Exception $e) {
    // Discard any output that was captured
    ob_end_clean();
    
    // Log the error
    error_log("Error in fetch_quotes.php: " . $e->getMessage());
    
    // Return error as JSON with appropriate status code (if not already set)
    if (http_response_code() === 200) {
        http_response_code(500);
    }
    echo json_encode(['error' => $e->getMessage()]);
}
