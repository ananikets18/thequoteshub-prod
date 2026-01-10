<?php

class NotificationModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Method to create a new notification
  public function createNotification($userId, $senderId, $quoteId, $type)
  {
    try {
      $query = "INSERT INTO notifications (user_id, sender_id, quote_id, type, created_at) VALUES (?, ?, ?, ?, NOW())";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $this->conn->error);
      }
      $stmt->bind_param("iiis", $userId, $senderId, $quoteId, $type);
      return $stmt->execute();
    } catch (Exception $e) {
      return false;
    }
  }

  // Method to get the notification ID for a like
  public function getNotificationId($quoteAuthorId, $userId, $quoteId)
  {
    try {
      $stmt = $this->conn->prepare("SELECT id FROM notifications WHERE user_id = ? AND sender_id = ? AND quote_id = ? AND type = 'like'");
      if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $this->conn->error);
      }
      $stmt->bind_param("iii", $quoteAuthorId, $userId, $quoteId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($notificationId);

      if ($stmt->fetch()) {
        return $notificationId; // Return notification ID if found
      } else {
        return null; // Return null if no notification exists
      }
    } catch (Exception $e) {
      error_log("Error in getNotificationId: " . $e->getMessage());
      return null; // Return null in case of error
    }
  }

  // Method to get the count of unread notifications for a user
  public function getUnreadNotificationCount($userId)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
    if ($stmt === false) {
      die('Error preparing SQL: ' . $this->conn->error);
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return isset($data['unread_count']) ? (int)$data['unread_count'] : 0;
  }

  // Method to get all notifications for a user with pagination
  public function getAllNotifications($userId, $limit = 20, $offset = 0)
{
    try {
        // Get total count for pagination
        $countQuery = "SELECT 
                        (SELECT COUNT(*) FROM notifications WHERE user_id = ?) + 
                        (SELECT COUNT(*) FROM follow_notifications WHERE followed_user_id = ?) AS total";
        $countStmt = $this->conn->prepare($countQuery);
        $countStmt->bind_param("ii", $userId, $userId);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalCount = $countResult->fetch_assoc()['total'];
        
        // Notifications from the 'notifications' table (likes and saves)
        $query1 = "SELECT 
                      n.id, n.user_id, n.sender_id, n.quote_id, n.type, n.created_at, n.is_read, 
                      u.user_img AS sender_img, u.username AS user_name, u.name AS sender_name, 
                      COALESCE(q.quote_text, '[Quote deleted]') AS quote_text
                    FROM notifications n
                    LEFT JOIN users u ON n.sender_id = u.id  
                    LEFT JOIN quotes q ON n.quote_id = q.id  
                    WHERE n.user_id = ? 
                    ORDER BY n.created_at DESC";
        
        // Notifications from the 'follow_notifications' table (follows)
        $query2 = "SELECT 
                      fn.id, fn.follower_id AS sender_id, fn.followed_user_id AS user_id, fn.created_at, fn.is_read, 
                      u.user_img AS sender_img, u.username AS user_name, u.name AS sender_name, 
                      NULL AS quote_text
                    FROM follow_notifications fn
                    LEFT JOIN users u ON fn.follower_id = u.id
                    WHERE fn.followed_user_id = ?
                    ORDER BY fn.created_at DESC";
        
        // Prepare the first query (likes and saves)
        $stmt1 = $this->conn->prepare($query1);
        if (!$stmt1) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        $stmt1->bind_param("i", $userId);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $notifications = $result1->fetch_all(MYSQLI_ASSOC);
        
        // Prepare the second query (follows)
        $stmt2 = $this->conn->prepare($query2);
        if (!$stmt2) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        $stmt2->bind_param("i", $userId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $followNotifications = $result2->fetch_all(MYSQLI_ASSOC);
        
        // Add a 'type' field to the follow notifications to keep the structure consistent
        foreach ($followNotifications as &$followNotification) {
            $followNotification['type'] = 'follow'; // Set type as 'follow' for follow notifications
            $followNotification['quote_text'] = 'You have a new follower!'; // Placeholder for follow notifications
        }
        
        // Combine the notifications
        $combinedNotifications = array_merge($notifications, $followNotifications);
        
        // Sort the combined notifications by the created_at field (most recent first)
        usort($combinedNotifications, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Apply pagination
        $paginatedNotifications = array_slice($combinedNotifications, $offset, $limit);
        
        return [
            'notifications' => $paginatedNotifications,
            'total' => $totalCount,
            'hasMore' => ($offset + $limit) < $totalCount
        ];
    } catch (Exception $e) {
        return [
            'notifications' => [],
            'total' => 0,
            'hasMore' => false
        ];
    }
}



  // Method to get unread notifications for a user
  public function getUnreadNotifications($userId)
  {
    try {
      $query = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $this->conn->error);
      }
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $result = $stmt->get_result();

      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return [];
    }
  }

  // Method to mark all notifications as read for a user
    public function markAllAsRead($userId)
    {
        try {
            $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }
            $stmt->bind_param("i", $userId);
            $stmt->execute();
    
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            return false;
        }
    }

  
  public function markAllFollowNotificationsAsRead($userId)
{
    try {
        $query = "UPDATE follow_notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    } catch (Exception $e) {
        return false;
    }
}

  
  // Method to create a follow notification
    public function createFollowNotification($followerId, $followedUserId)
    {
      try {
        // Prepare the SQL query to insert a follow notification
        $query = "INSERT INTO follow_notifications (follower_id, followed_user_id, created_at, is_read) VALUES (?, ?, NOW(), 0)";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
          throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
    
        // Bind the follower ID and followed user ID as parameters
        $stmt->bind_param("ii", $followerId, $followedUserId);
    
        // Execute the statement
        return $stmt->execute();
      } catch (Exception $e) {
        return false;
      }
    }

    // Method to check if a follow notification already exists
public function checkFollowNotificationExists($followerId, $followedUserId)
{
    try {
        $query = "SELECT COUNT(*) FROM follow_notifications WHERE follower_id = ? AND followed_user_id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $followerId, $followedUserId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);

        if ($stmt->fetch()) {
            return $count > 0; // Return true if a notification already exists
        } else {
            return false; // Return false if no notification exists
        }
    } catch (Exception $e) {
        return false; // Return false in case of error
    }
}


}
