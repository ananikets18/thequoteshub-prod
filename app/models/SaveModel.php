<?php

class SaveModel
{
    private $conn;
    private $NotificationModel; // Add NotificationModel
    private $sender_id; // Property to store sender_id

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->NotificationModel = new NotificationModel($conn); // Initialize NotificationModel
        $this->sender_id = null; // Initialize sender_id to null
    }

    // Method to set sender_id before calling removeSave or addSave
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    // Method to check if the quote is saved
    public function isSaved($user_id, $quote_id)
    {
        $stmt = $this->conn->prepare("SELECT 1 FROM saves WHERE user_id = ? AND quote_id = ?");
        $stmt->bind_param("ii", $user_id, $quote_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Method to add a saved quote
    public function addSave($user_id, $quote_id)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO saves (user_id, quote_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $quote_id);
            $result = $stmt->execute();

            if (!$result) {
                error_log("Database error: " . $this->conn->error);
            }

            // Optionally, send notification after adding save
            if ($result && $this->sender_id !== null) {
                $this->NotificationModel->sendSaveNotification($this->sender_id, $user_id, $quote_id);
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in addSave: " . $e->getMessage());
            return false;
        }
    }

    // Method to remove a saved quote
    public function removeSave($user_id, $quote_id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM saves WHERE user_id = ? AND quote_id = ?");
            $stmt->bind_param("ii", $user_id, $quote_id);
            $saveRemoved = $stmt->execute();

            // Optionally, remove notification after removing save
            if ($saveRemoved && $this->sender_id !== null) {
                $this->NotificationModel->removeSaveNotification($this->sender_id, $user_id, $quote_id);
            }

            return $saveRemoved;
        } catch (Exception $e) {
            error_log("Error in removeSave: " . $e->getMessage());
            return false;
        }
    }


  public function getTotalSavesByUserId($userId)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_saves FROM saves WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['total_saves'] ? $row['total_saves'] : 0; // Return total saves, default to 0 if null
  }



  public function getSaveCount($quote_id)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS save_count FROM saves WHERE quote_id = ?");
    $stmt->bind_param("i", $quote_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return isset($data['save_count']) ? $data['save_count'] : 0;
  }
  
   public function getUserSaveCount($user_id)
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(*) AS save_count FROM saves WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();

      return isset($data['save_count']) ? $data['save_count'] : 0;
    } catch (Exception $e) {
      error_log("Error in getUserSaveCount: " . $e->getMessage());
      return 0;
    }
  }
}
