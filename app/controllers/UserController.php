<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../models/FollowModel.php';
require_once __DIR__ . '/../../config/utilis.php';

class UserController
{
  private $userModel;
  private $quoteModel;
  private $notificationModel;
  private $followModel;
  public function __construct($conn)
  {

    $this->userModel = new UserModel($conn);
    $this->quoteModel = new quoteModel($conn);
    $this->notificationModel = new notificationModel($conn);
    $this->followModel = new followModel($conn);
  }

public function register()
{
    if (isset($_SESSION['user_id'])) {
        header("Location: /dashboard");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitizeInput(trim($_POST['name']));
        $username = sanitizeInput(trim($_POST['username']));
        $email = sanitizeInput(trim($_POST['email']));
        $password = trim($_POST['password']);
        $csrfToken = trim($_POST['csrf_token']);

        $response = [
            'status' => 'error',
            'message' => 'An error occurred. Please try again.'
        ];

        if (!validateCsrfToken($csrfToken)) {
            $response['message'] = 'Invalid CSRF token.';
            echo json_encode($response);
            return;
        }

        if (empty($name) || empty($username) || empty($email) || empty($password)) {
            $response['message'] = 'All fields are required.';
            echo json_encode($response);
            return;
        }

        if (!validateEmail($email)) {
            $response['message'] = 'Invalid email format.';
            echo json_encode($response);
            return;
        }

        try {
            if ($this->userModel->register($name, $username, $email, $password)) {
                // Regenerate session ID after registration
                session_regenerate_id(true);
                
                // Set user session after registration
                $userId = $this->userModel->getUserIdByEmail($email);
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['username'] = $username;
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    
                    // Regenerate CSRF token
                    unset($_SESSION['csrf_token']);
                    generateCsrfToken();
                }
                
                $response['status'] = 'success';
                $response['message'] = 'Registration successful. Redirecting...';
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage(); // Display the specific error
            echo json_encode($response);
        }
    } else {
        require_once __DIR__ . '/../views/users/register.php';
    }
}

public function registerWithGoogle($name, $email)
{
    //  if (isset($_SESSION['user_id'])) {
    //     header("Location: /dashboard");
    //     exit();
    // }


    $response = [
        'status' => 'error',
        'message' => 'An error occurred. Please try again.'
    ];

    // Attempt to register the user with Google
    try {
        if ($this->userModel->registerWithGoogle($name, $email)) {
            // Regenerate session ID after registration
            session_regenerate_id(true);
            
            $response['status'] = 'success';
            $response['message'] = 'Registration successful. Redirecting to dashboard...';
            $_SESSION['user_id'] = $this->userModel->getUserIdByEmail($email); // Assuming you have this method
            
            // Regenerate CSRF token
            unset($_SESSION['csrf_token']);
            generateCsrfToken();
            
            echo json_encode($response);
        } else {
            $response['message'] = 'User already exists. Please log in.';
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        echo json_encode($response);
    }
}

public function showRegistrationForm()
{
    require_once __DIR__ . '/../views/users/register.php';
}

public function login()
{
    // Redirect to dashboard if user is already logged in
    if (isset($_SESSION['user_id'])) {
        header("Location: /dashboard");
        exit();
    }

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ensure $_POST values are not null
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Basic validation
        if (empty($username) || empty($password)) {
            $error = 'All fields are required.';
            require_once __DIR__ . '/../views/users/login.php';
            return;
        }

              // Authenticate user
              $user = $this->userModel->getUserByUsername($username);
        
              // Check if user exists and verify password
              if ($user && password_verify($password, $user['password_hash'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                
        
                // Fetch the user data to check for the badge awarding
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_img'] = $user['user_img']; 
                
                 // Fetch unread notification count
                $_SESSION['unread_count'] = $this->notificationModel->getUnreadNotificationCount($user['id']);
        
                // Regenerate CSRF token after login
                unset($_SESSION['csrf_token']);
                generateCsrfToken();
        
                // Update user status to active (1)
                $this->userModel->updateUserStatus($user['id'], 1);
        
                header("Location: /");
                exit();
      
         } else {
            $error = 'Invalid username or password.';
            require_once __DIR__ . '/../views/users/login.php';
        }
    } else {
        require_once __DIR__ . '/../views/users/login.php';
    }
}

  
public function logout()
{
    session_start();
    if (isset($_SESSION['user_id'])) {
        // Update status to inactive (0)
        $result = $this->userModel->updateUserStatus($_SESSION['user_id'], 0);
        if (!$result) {
            error_log("Failed to update user status to inactive for user ID: " . $_SESSION['user_id']);
        } else {
            error_log("User status updated to inactive for user ID: " . $_SESSION['user_id']);
        }

        // Clear session data
        session_unset();
        session_destroy();
    }

    header("Location: /"); // Redirect to login or home
    exit();
}

public function showLikedQuotes()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $likedQuotes = $this->quoteModel->getUserLikedQuotes($userId);
    require_once __DIR__ . '/../views/pages/liked-quotes.php';
}


public function showSavedQuotes()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $savedQuotes = $this->quoteModel->getUserSavedQuotes($userId);
    require_once __DIR__ . '/../views/pages/saved-quotes.php';
}


  public function profile()
  {
      
      
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user_id = $_SESSION['user_id'];
      $name = $_POST['name'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $bio = $_POST['bio'];
      $imagePath = null;

      if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['profile_image'];
        $targetDir = __DIR__ . "/../../public/uploads/users/";

        // Generate a unique hash for the filename
        $hash = md5(uniqid(rand(), true));
        $fileExtension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $newFilename = $hash . '.' . $fileExtension;
        $targetFile = $targetDir . $newFilename;

        // Check if image file is a valid image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
          echo json_encode(['status' => 'error', 'message' => 'File is not an image.']);
          return;
        }

        // Check file size
        if ($image["size"] > 2000000) {
          echo json_encode(['status' => 'error', 'message' => 'File is too large.']);
          return;
        }

        // Allow certain file formats
        if (!in_array($fileExtension, ["jpg", "png", "jpeg", "gif"])) {
          echo json_encode(['status' => 'error', 'message' => 'Invalid file format.']);
          return;
        }

        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
          $imagePath = $newFilename; // Store just the filename in the database
        } else {
          echo json_encode(['status' => 'error', 'message' => 'File upload failed.']);
          return;
        }
      }

      // Update the user's profile
      $updateResult = $this->userModel->updateUser($user_id, $name, $username, $email, $bio, $imagePath);

      if ($updateResult) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile.']);
      }
    } else {
      $user_id = $_SESSION['user_id'];
      $user = $this->userModel->getUserById($user_id);
      require_once __DIR__ . '/../views/users/profile.php';
    }
  }

  public function changePassword()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user_id = $_SESSION['user_id'];
      $current_password = $_POST['current_password'];
      $new_password = $_POST['new_password'];
      $confirm_password = $_POST['confirm_password'];

      // Validate inputs
      if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode([
          'status' => 'error',
          'message' => 'All fields are required.'
        ]);
        return;
      }

      if ($new_password !== $confirm_password) {
        echo json_encode([
          'status' => 'error',
          'message' => 'New password and confirm password do not match.'
        ]);
        return;
      }

      // Check if the current password is correct
      $isValid = $this->userModel->verifyPassword($user_id, $current_password);

      if (!$isValid) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Current password is incorrect.'
        ]);
        return;
      }

      // Update the password in the model
      $success = $this->userModel->updatePassword($user_id, $new_password);

      if ($success) {
        echo json_encode([
          'status' => 'success',
          'message' => 'Password changed successfully.'
        ]);
      } else {
        echo json_encode([
          'status' => 'error',
          'message' => 'Error updating password.'
        ]);
      }
    } else {
      // Load the change password view
      require_once __DIR__ . '/../views/users/changePassword.php';
    }
  }
  
    public function showTopUsers() {
        $topUsers = $this->userModel->getTopUsersByQuotes();
        
        require_once __DIR__ . '/../views/pages/top-users.php';
    }
    
    

 // UserController class method to show user profile
  public function showUserProfile($username)
  {
    // Fetch user data from the model
    $userData = $this->userModel->getUserByUsername($username);
    
    // Check if user exists
    if ($userData) {
      // Fetch quotes by the user ID, ensuring to use the correct identifier
      $quotes = $this->userModel->getQuotesByUsername($userData['username']);

      // Fetch badges using the username
      $badges = $this->userModel->getBadgesByUsername($username);
      
        // Fetch quote count for the user
      $quoteCount = $this->userModel->getQuoteCountByUsername($username);
    
        
        // Determine if the user is verified
        $isVerified = ($quoteCount >= 20);
        
        $followerCount = $this->followModel->getFollowersCount($userData['id']); // Use user ID for fetching followers
        $followingCount = $this->followModel->getFollowingCount($userData['id']); // Use user ID for fetching following
        
        // var_dump($followerCount,$followingCount);

      // Pass the user data, quotes, and badges to the view directly
      require_once __DIR__ . '/../views/user.php'; // Adjusted to load from main views directory
    } else {
      // Handle user not found case by showing a 404 page
      require_once __DIR__ . '/../views/pages/404-page.php';
    }
  }
     // Method to get the unread notification count and render the header
    public function renderHeader($userId) 
    {
        // Get the unread notification count
        $unreadCount = $this->getUnreadNotificationCount($userId);
        // var_dump($unreadCount);
        $_SESSION['unread_count'] = $unreadCount; // Store in session
        // Include the header view and pass the unread count
        require_once __DIR__ . '/../views/components/header.php';
    }
    


    
}