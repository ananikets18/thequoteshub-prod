<?php
require_once   __DIR__ . '/database.php';



function getUnreadNotificationCount($conn, $userId)
{
  $stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result) {
    $data = $result->fetch_assoc();
    return (int)$data['unread_count'];
  } else {
    error_log("Database error: " . $conn->error);
    return 0; // Return 0 if there's an error
  }
}
