<?php

require_once __DIR__ . '/../models/NotificationModel.php';

class NotificationController
{
  private $conn;
  private $notificationModel;
  private $quoteModel;
  private $userModel;

  public function __construct($conn)
  {
    $this->notificationModel = new NotificationModel($conn);
    $this->conn = $conn;
    $this->quoteModel = new QuoteModel($conn);
    $this->userModel = new UserModel($conn);
  }
    
    
  // Method to show unread notifications for a user
  public function showUnreadNotifications($userId)
  {
    // Fetch unread notifications from the model
    $notifications = $this->notificationModel->getUnreadNotifications($userId);
 
    // Load the view for notifications and pass the notifications data
    require_once __DIR__ . '/../views/pages/notifications.php';
  }
    
      // Method to get the unread notification count and render the header
public function renderHeader() 
{
    // Start the session if it's not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Ensure the session is active
    }

    // Check if the user ID exists in the session
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id']; // Get the user ID from the session
        
        // Get the unread notification count
        $unreadCount = $this->getUnreadNotificationCount($userId);
        
        // Store the unread count in session
        $_SESSION['unread_count'] = $unreadCount; 
        
        // Include the header view where the unread count will be displayed
        require_once __DIR__ . '/../views/components/header.php';
    } else {
        // Handle the case where the user is not logged in or no user ID is available
        error_log("User ID not found in session.");
        // You could redirect to a login page or show an error
    }
}

    
    
 // Method to get unread notification count
    public function getUnreadNotificationCount($userId) 
    {
        return $this->notificationModel->getUnreadNotificationCount($userId);
    }
    

  public function createNotification($userId, $senderId, $quoteId, $actionType)
  {
    // Validate input and create a notification
    if ($this->isValidUser($userId) && $this->isValidUser($senderId)) {
      // Create the notification
      $this->notificationModel->createNotification($userId, $senderId, $quoteId, $actionType);

      // Award points based on the action
      switch ($actionType) {
        case 'like':
          if ($this->userModel->updatePoints($senderId, 5)) {
            error_log("Points awarded for like: User ID $senderId received 5 points.");
          } else {
            error_log("Failed to update points for user ID $senderId on like.");
          }
          break;
        case 'save':
          if ($this->userModel->updatePoints($senderId, 3)) {
            error_log("Points awarded for save: User ID $senderId received 3 points.");
          } else {
            error_log("Failed to update points for user ID $senderId on save.");
          }
          break;
        case 'create':
          if ($this->userModel->updatePoints($senderId, 10)) {
            error_log("Points awarded for create: User ID $senderId received 10 points.");
          } else {
            error_log("Failed to update points for user ID $senderId on create.");
          }
          break;
        default:
          // Handle unknown action type if necessary
          error_log("Unknown action type: $actionType.");
          break;
      }
    }
  }

  protected function isValidUser($userId)
  {
    // Check if the user exists in the database
    return $this->userModel->getUserById($userId) !== null; // Assuming getUserById returns null if not found
  }
  
  public function markAllAsRead($userId)
{
    if ($this->notificationModel->markAllAsRead($userId)) {
        error_log("All notifications marked as read for user ID: $userId.");
        return true;
    } else {
        error_log("Failed to mark all notifications as read for user ID: $userId.");
        return false;
    }
}

  
public function markAllFollowNotificationsAsRead($userId)
{
    if ($this->notificationModel->markAllFollowNotificationsAsRead($userId)) {
        error_log("All follow notifications marked as read for user ID: $userId.");
        return true;
    } else {
        error_log("Failed to mark all follow notifications as read for user ID: $userId.");
        return false;
    }
}

}
