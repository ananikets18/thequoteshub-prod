<?php

class LikeModel
{
  private $conn;
  private $NotificationModel;
  private $sender_id; // Add a property to store sender_id

  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->NotificationModel = new NotificationModel($conn);
    $this->sender_id = null; // Initialize sender_id to null
  }
 
 // Method to set sender_id before calling removeLike
  public function setSenderId($sender_id)
  {
    $this->sender_id = $sender_id;
  }


  public function addLike($user_id, $quote_id)
  {
    try {
      $stmt = $this->conn->prepare("INSERT INTO likes (user_id, quote_id) VALUES (?, ?)");
      $stmt->bind_param("ii", $user_id, $quote_id);
      $result = $stmt->execute();

      if (!$result) {
        error_log("Database error: " . $this->conn->error);
      }

      return $result;
    } catch (Exception $e) {
      error_log("Error in addLike: " . $e->getMessage());
      return false;
    }
  }

 public function removeLike($user_id, $quote_id)
  {
    try {

      $stmt = $this->conn->prepare("DELETE FROM likes WHERE user_id = ? AND quote_id = ?");
      $stmt->bind_param("ii", $user_id, $quote_id);
      $likeRemoved = $stmt->execute();



      return $likeRemoved;
    } catch (Exception $e) {
     error_log("Error in removeLike: " . $e->getMessage());
      return false;
    }
  }

  public function isLiked($user_id, $quote_id)
  {
    $stmt = $this->conn->prepare("SELECT 1 FROM likes WHERE user_id = ? AND quote_id = ?");
    $stmt->bind_param("ii", $user_id, $quote_id);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
  }
  
    public function getTotalLikesByUserId($userId)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['total_likes'] ? $row['total_likes'] : 0; // Return total likes, default to 0 if null
  }

  public function getLikeCount($quote_id)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE quote_id = ?");
    $stmt->bind_param("i", $quote_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return isset($data['like_count']) ? $data['like_count'] : 0;
  }
  
   public function getUserLikeCount($user_id)
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();

      return isset($data['like_count']) ? $data['like_count'] : 0;
    } catch (Exception $e) {
      error_log("Error in getUserLikeCount: " . $e->getMessage());
      return 0;
    }
  }
}
