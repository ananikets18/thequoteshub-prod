<?php 
require_once __DIR__ . '/../models/FollowModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AuthorModel.php';
require_once __DIR__ . '/../models/LikeModel.php';
require_once __DIR__ . '/../models/SaveModel.php';  
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../../config/utilis.php';

class FollowController
{
  private $conn;
  private $followModel;
  private $notificationModel;
  private $quoteModel;
  private $authorModel;
  private $userModel;
  private $likeModel;
  private $saveModel;
  private $categoryModel;

  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->followModel = new FollowModel($conn);
    $this->notificationModel = new notificationModel($conn);
    $this->likeModel = new LikeModel($conn);
    $this->saveModel = new SaveModel($conn);
    $this->userModel = new UserModel($conn);
    $this->authorModel = new AuthorModel($conn);
    $this->categoryModel = new CategoryModel($conn);
      $this->quoteModel = new QuoteModel($conn);
  }


      public function index()
    {
        // Default initialization of the data arrays
        $isFollowing = [];
        $followersCounts = [];
        $followingCounts = [];
    
        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
    
                // Iterate over the list of users (replace with actual data)
                foreach ($users as $user) {
                    // Check if the current user is following this user
                    $isFollowing[$user['id']] = $this->followModel->isFollowing($userId, $user['id']);
                    
                    // Get the count of followers for this user
                    $followersCounts[$user['id']] = $this->followModel->getFollowersCount($user['id']);
                    
                    // Get the count of users this user is following
                    $followingCounts[$user['id']] = $this->followModel->getFollowingCount($user['id']);
                }
                } else {
                    // If the user is not logged in, assume no follow status
                    foreach ($users as $user) {
                        // Not following any users by default when not logged in
                        $isFollowing[$user['id']] = false;
                        
                        // Get the followers count for each user
                        $followersCounts[$user['id']] = $this->followModel->getFollowersCount($user['id']);
                        
                        // Get the following count for each user
                        $followingCounts[$user['id']] = $this->followModel->getFollowingCount($user['id']);
                    }
                }
    
        // Example of how you might return or use this data (e.g., pass it to the view)
        return [
            'isFollowing' => $isFollowing,
            'followersCounts' => $followersCounts,
            'followingCounts' => $followingCounts,
        ];
    }
    
    public function toggleFollow($followerId, $followedUserId)
    {
        if ($followerId === $followedUserId) {
            echo json_encode(['success' => false, 'message' => 'You cannot follow yourself.']);
            return;
        }
    
        // Check if the user is already following
        $isFollowing = $this->followModel->isFollowing($followerId, $followedUserId);
    
        if ($isFollowing) {
            // Unfollow the user
            $result = $this->followModel->unfollowUser($followerId, $followedUserId);
            echo json_encode($result
                ? [
                    'success' => true,
                    'message' => 'User unfollowed successfully.',
                    'is_following' => false, // User is no longer following
                    'action' => 'unfollow'
                ]
                : ['success' => false, 'message' => 'Failed to unfollow the user.']);
        } else {
            // Follow the user
            $result = $this->followModel->followUser($followerId, $followedUserId);
    
            // Ensure unique follow notifications by checking if it already exists
            $notificationExists = $this->notificationModel->checkFollowNotificationExists($followerId, $followedUserId);
    
            if (!$notificationExists) {
                // Create a follow notification
                $notificationResult = $this->notificationModel->createFollowNotification($followerId, $followedUserId);
    
                if (!$notificationResult) {
                    echo json_encode(['success' => false, 'message' => 'Failed to create follow notification.']);
                    return;
                }
            }
    
            echo json_encode($result
                ? [
                    'success' => true,
                    'message' => 'User followed successfully.',
                    'is_following' => true, // User is now following
                    'action' => 'follow'
                ]
                : ['success' => false, 'message' => 'Failed to follow the user.']);
        }
}
            
    public function getFollowStatus()
    {
        // Ensure the parameters are being passed correctly via GET
        if (isset($_GET['follower_id']) && isset($_GET['followed_user_id'])) {
            $followerId = (int) $_GET['follower_id'];
            $followedUserId = (int) $_GET['followed_user_id'];
            
            // Now check if the user is following
            $isFollowing = $this->followModel->isFollowing($followerId, $followedUserId);
    
            // Log the response to ensure it's correctly structured
            $response = [
                'success' => true,
                'is_following' => $isFollowing
            ];
    
            // Log the response to check it before sending it
            error_log(json_encode($response)); // You can view this in your PHP error log
            
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Required parameters are missing']);
        }
}


    // In your FollowController
    public function getFollowCounts()
    {
        $userId = $_GET['user_id'];
    
        if (isset($userId)) {
            $followersCount = $this->followModel->getFollowersCount($userId);
            $followingCount = $this->followModel->getFollowingCount($userId);
    
            echo json_encode([
                'success' => true,
                'followers_count' => $followersCount,
                'following_count' => $followingCount,
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        }
    }
    
        public function getFollowersList($userId)
        {
            $followers = $this->followModel->getFollowersList($userId);
            echo json_encode([
                'success' => true,
                'followers' => $followers
            ]);
        }
        public function getFollowingList($userId)
        {
            $following = $this->followModel->getFollowingList($userId);
            echo json_encode([
                'success' => true,
                'following' => $following
            ]);
        }
    
        public function getFollowing(){
            // Pass data to the view
            
    // Fetch users with most quotes, likes, and saves
    $topUsers = $this->quoteModel->getTopUsers();
    // var_dump($topUsers);
    
    if ($topUsers === false) {
        error_log("Error fetching top users.");
        $topUsers = []; // Fallback to empty array on error
    }
        require_once __DIR__ . '/../views/following/index.php';
        }

        // public function markAllAsRead($userId)
        // {
        //     // Mark all notifications as read for the user
        //     if ($this->notificationModel->markAllAsRead($userId)) {
        //         error_log("All notifications marked as read for user ID: $userId.");
        //         return true; // Indicate success
        //     } else {
        //         error_log("Failed to mark all notifications as read for user ID: $userId.");
        //         return false; // Indicate failure
        //     }
        // }



}
