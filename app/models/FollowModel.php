<?php 

class FollowModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    
  public function isFollowing($followerId, $followedUserId)
{
    $query = "SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?";
    $stmt = $this->conn->prepare($query);

    // Check if the statement preparation is successful
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $this->conn->error);
        return false;
    }

    // Bind the user IDs as integers
    $stmt->bind_param('ii', $followerId, $followedUserId);

    // Execute the query
    if (!$stmt->execute()) {
        error_log("Failed to execute query: " . $stmt->error);
        return false;
    }

    // Get the result from the query
    $result = $stmt->get_result();

    // Check if the result has any rows
    if ($result && $result->num_rows > 0) {
        return true; // User is following
    } else {
        return false; // User is not following
    }
}


    
    // Follow a user
    public function followUser($followerId, $followedUserId)
    {
        if (empty($followedUserId) || is_null($followedUserId)) {
            throw new Exception('Invalid followed user ID');
        }
        
        if ($followerId == $followedUserId) {
            throw new Exception('You cannot follow yourself');
        }

        $query = "INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Failed to prepare the statement');
        }

        $stmt->bind_param('ii', $followerId, $followedUserId);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute the query');
        }
        return true;
    }

    // Unfollow a user
    public function unfollowUser($followerId, $followedUserId)
    {
        if ($followerId == $followedUserId) {
            throw new Exception('You cannot unfollow yourself');
        }

        $query = "DELETE FROM follows WHERE follower_id = ? AND followed_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Failed to prepare the statement');
        }

        $stmt->bind_param('ii', $followerId, $followedUserId);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute the query');
        }
        return true;
    }

    // Get the count of followers for a specific user
    public function getFollowersCount($followedUserId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS follower_count FROM follows WHERE followed_id = ?");
        $stmt->bind_param("i", $followedUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        return isset($data['follower_count']) ? $data['follower_count'] : 0;
    }
    
    // Get the count of users a specific user is following
    public function getFollowingCount($followerId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS following_count FROM follows WHERE follower_id = ?");
        $stmt->bind_param("i", $followerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        return isset($data['following_count']) ? $data['following_count'] : 0;
    }
    
    public function getFollowersList($followedUserId)
    {
        $stmt = $this->conn->prepare("SELECT u.username AS username, u.name AS user_name, u.user_img  FROM users u JOIN follows f ON u.id = f.follower_id WHERE f.followed_id =  ?");
        $stmt->bind_param("i", $followedUserId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $followers = [];
        while ($row = $result->fetch_assoc()) {
            $followers[] = $row;
        }
    
        error_log("Fetched followers: " . json_encode($followers)); // Log the results to check
        return $followers;
    }

        public function getFollowingList($followerId)
    {
        $stmt = $this->conn->prepare("SELECT u.name AS user_name, u.username as username, u.user_img as user_img FROM users u JOIN follows f ON u.id = f.followed_id WHERE f.follower_id =  ?");
        $stmt->bind_param("i", $followerId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $following = [];
        while ($row = $result->fetch_assoc()) {
            $following[] = $row;
        }
    
        return $following;
    }

}
