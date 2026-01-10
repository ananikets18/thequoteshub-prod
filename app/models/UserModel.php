<?php


class UserModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getUserById($userId)
  {
    $stmt = $this->conn->prepare("SELECT name, username, email, user_img, points, bio FROM users WHERE id = ?");
    if ($stmt === false) {
      // Handle error in statement preparation
      die('Error preparing SQL: ' . $this->conn->error);
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Make sure to check if data exists
  }
  
 public function register($name, $username, $email, $password)
{
    // Check if the username is valid (no spaces or special characters)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        throw new Exception("Username can only contain letters, numbers, and underscores, and no spaces.");
    }

    // Check if email or username already exists
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        throw new Exception("Email or username already exists.");
    }
    $stmt->close();

    // Insert new user
    $stmt = $this->conn->prepare("INSERT INTO users (name, username, email, password_hash, bio) VALUES (?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $bio = ""; // Default empty bio for new users
    $stmt->bind_param("sssss", $name, $username, $email, $hashedPassword, $bio);

    if ($stmt->execute()) {
        $stmt->close();
        return true; // Registration successful
    } else {
        $stmt->close();
        throw new Exception("Registration failed. Please try again.");
    }
}

public function getUserIdByEmail($email) {
    // Prepare the SQL statement
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
    
    // Bind the parameter
    $stmt->bind_param('s', $email);
    
    // Execute the statement
    $stmt->execute();
    
    // Store the result
    $stmt->store_result();
    
    // Check if a user with the given email exists
    if ($stmt->num_rows > 0) {
        // Bind the result to a variable
        $stmt->bind_result($userId);
        
        // Fetch the result
        $stmt->fetch();
        
        return $userId; // Return the user ID
    } else {
        return null; // Return null if user not found
    }
    
    // Close the statement
    $stmt->close();
}




public function registerWithGoogle($name, $email)
{
    // Check if the user already exists
    $query = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('s', $email); // 's' denotes the type is a string
    $stmt->execute();
    $stmt->store_result(); // Store the result to get row count

    if ($stmt->num_rows > 0) {
        $stmt->close();
        return true; // User already exists
    }

    // If user doesn't exist, insert them
    // Generate username from email (before @ symbol)
    $username = explode('@', $email)[0];
    // Ensure username is unique by appending random numbers if needed
    $originalUsername = $username;
    $counter = 1;
    while ($this->usernameExists($username)) {
        $username = $originalUsername . $counter;
        $counter++;
    }
    
    $bio = ""; // Default empty bio
    $query = "INSERT INTO users (name, username, email, bio, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('ssss', $name, $username, $email, $bio);

    $result = $stmt->execute();
    $stmt->close(); // Close the statement
    return $result; // Return true on successful insert, false otherwise
}




    // Helper method to check if username exists
    private function usernameExists($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

  public function authenticate($username, $password)
  {
    $stmt = $this->conn->prepare("SELECT id, password_hash, created_at FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
      if (password_verify($password, $row['password_hash'])) {
        // Set user details in session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['created_at'] = $row['created_at']; // Store the joined_date in session

        return $row['id'];
      }
    }
    return false;
  }

   // Method to update user points
  public function updatePoints($userId, $points)
  {
    $query = "UPDATE users SET points = points + ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
      error_log("Failed to prepare statement: " . $this->conn->error);
      return false; // Handle error as needed
    }
    $stmt->bind_param("ii", $points, $userId);
    return $stmt->execute();
  }

    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT id, name, username, email, created_at FROM users");
         if ($stmt === false) {
          // Handle error in statement preparation
          die('Error preparing SQL: ' . $this->conn->error);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Fetch all users as an associative array
    }
    
    public function getAllUsersWithQuoteCount()
{
    // Query to join users with quotes and count the number of quotes per user
    $query = "
        SELECT 
            u.id, u.name, u.username, u.email, u.created_at, 
            COUNT(q.id) AS total_quotes
        FROM 
            users u
        LEFT JOIN 
            quotes q ON u.id = q.user_id
        GROUP BY 
            u.id, u.name, u.username, u.email, u.created_at
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Fetch all users with quote count as an associative array
}

// Get paginated users with quote count for analytics
public function getAllUsersWithQuoteCountPaginated($limit, $offset)
{
    $query = "
        SELECT 
            u.id, u.name, u.username, u.email, u.created_at, 
            COUNT(q.id) AS total_quotes
        FROM 
            users u
        LEFT JOIN 
            quotes q ON u.id = q.user_id
        GROUP BY 
            u.id, u.name, u.username, u.email, u.created_at
        ORDER BY u.created_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('ii', $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get total users count
public function getTotalUsersCount()
{
    $query = "SELECT COUNT(*) as total FROM users";
    $result = $this->conn->query($query);
    $row = $result->fetch_assoc();
    return (int)$row['total'];
}

// Search users with quote count for analytics with pagination
public function searchUsersWithQuoteCountPaginated($search, $limit, $offset)
{
    $searchTerm = "%" . $search . "%";
    $query = "
        SELECT 
            u.id, u.name, u.username, u.email, u.created_at, 
            COUNT(q.id) AS total_quotes
        FROM 
            users u
        LEFT JOIN 
            quotes q ON u.id = q.user_id
        WHERE u.name LIKE ? OR u.username LIKE ? OR u.email LIKE ?
        GROUP BY 
            u.id, u.name, u.username, u.email, u.created_at
        ORDER BY u.created_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get count of searched users
public function getSearchUsersCount($search)
{
    $searchTerm = "%" . $search . "%";
    $query = "SELECT COUNT(*) as total FROM users WHERE name LIKE ? OR username LIKE ? OR email LIKE ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return (int)$row['total'];
}

  public function getUserDetails($user_id)
  {
    if (!is_int($user_id)) {
      throw new InvalidArgumentException("Invalid user ID");
    }

    $stmt = $this->conn->prepare("SELECT name, username, bio, user_img, created_at FROM users WHERE id = ?");
    if ($stmt === false) {
      throw new Exception("Failed to prepare statement: " . $this->conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function getUserByUsername($username)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
  
  // Count the number of quotes for a username
public function getQuoteCountByUsername($username)
{
    $query = "
        SELECT 
            COUNT(q.id) AS quote_count
        FROM 
            quotes q
        INNER JOIN 
            users u ON q.user_id = u.id
        WHERE 
            u.username = ?;
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['quote_count']; // Return the count of quotes
}


  // Fetch all quotes for a username or user
  public function getQuotesByUsername($username)
  {
    $query = "
        SELECT 
            q.quote_text, 
            q.author_name, 
            q.id AS quote_id, 
            q.created_at AS quote_created_at, 
            u.id AS user_id, 
            u.name AS user_name, 
            u.username, 
            u.created_at AS user_created_at, 
            u.bio, 
            u.user_img 
        FROM 
            quotes q
        INNER JOIN 
            users u ON q.user_id = u.id
        WHERE 
            u.username = ?
        ORDER BY 
            q.created_at DESC;
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }
  
  
   public function getBadgesByUsername($username)
  {
    // Prepare the SQL statement to fetch badges for a given username
    $stmt = $this->conn->prepare("
        SELECT ub.badge_name, ub.awarded_at
        FROM users u
        JOIN user_badges ub ON u.id = ub.user_id
        WHERE u.username = ?; 
    ");

    // Bind the username parameter
    $stmt->bind_param('s', $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch all badges as an associative array
    return $result->fetch_all(MYSQLI_ASSOC);
  }


  // Check if the user already has a specific badge
  public function hasBadge($user_id, $badgeName)
  {
    $stmt = $this->conn->prepare("SELECT * FROM user_badges WHERE user_id = ? AND badge_name = ?");
    $stmt->bind_param("is", $user_id, $badgeName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;  // Return true if the badge exists
  }

  // Award a badge to the user
  public function awardBadge($user_id, $badgeName)
  {
    $stmt = $this->conn->prepare("INSERT INTO user_badges (user_id, badge_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $badgeName);
    $stmt->execute();
  }

  // Method to get user badges
  public function getUserBadges($user_id)
  {
    $stmt = $this->conn->prepare("SELECT badge_name FROM user_badges WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $badges = $result->fetch_all(MYSQLI_ASSOC);
    return $badges;
  }
  
public function updateUserStatus($user_id, $status)
  {
    $stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param('ii', $status, $user_id);
    return $stmt->execute();
  }


  // Update the user's profile
public function updateUser($user_id, $name, $username, $email, $bio, $imagePath = null)
{
    $sql = "UPDATE users SET name = ?, username = ?, email = ?, bio = ?" . ($imagePath ? ", user_img = ?" : "") . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);

    if ($imagePath) {
        $stmt->bind_param("sssssi", $name, $username, $email, $bio, $imagePath, $user_id);
    } else {
        $stmt->bind_param("ssssi", $name, $username, $email, $bio, $user_id);
    }

    return $stmt->execute();
}
    public function getTopUsersByQuotes() {
        $sql = "
        SELECT users.id, users.username, users.name, users.user_img, COUNT(quotes.id) AS quote_count
        FROM users
        LEFT JOIN quotes ON users.id = quotes.user_id
        GROUP BY users.id
        ORDER BY quote_count DESC
        LIMIT 10
        ";

        try {
            $result = $this->conn->query($sql);

            // Check if the query was successful
            if ($result === false) {
                throw new Exception("Database Query Error: " . $this->conn->error);
            }

            $users = [];
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }

            return $users;
        } catch (Exception $e) {
            // Log the error or handle it as needed
            error_log($e->getMessage()); // Log the error message to the server's error log
            return []; // Return an empty array or handle the error as needed
        }
    }
    

  // Verify if the current password is correct
  public function verifyPassword($user_id, $current_password)
  {
    $stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the password matches
    return password_verify($current_password, $user['password_hash']);
  }
  

  // Update the user's password
  public function updatePassword($user_id, $new_password)
  {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
    $stmt = $this->conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    return $stmt->execute();
  }
}
