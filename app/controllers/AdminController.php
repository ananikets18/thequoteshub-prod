<?php

require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/BlogModel.php';
require_once __DIR__ . '/../models/QuoteModel.php'; 
require_once __DIR__ . '/../models/UserModel.php'; 
require_once __DIR__ . '/../../config/utilis.php';

class AdminController
{
  private $conn;
  private $blogModel;
  private $adminModel;
  private $quoteModel;
  private $userModel;
  
  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->adminModel = new AdminModel($conn);
    $this->userModel = new UserModel($conn);
    $this->blogModel = new BlogModel($conn);
    $this->quoteModel = new QuoteModel($conn); // Initialize QuoteModel
  }

public function login()
{   
    header('X-Robots-Tag: noindex, nofollow');
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize inputs
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validate inputs
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors['password'] = "Password is required.";
        }

        // If no errors, try to authenticate the user
        if (empty($errors)) {
            $admin = $this->adminModel->getAdminByEmail($email);

            if ($admin && password_verify($password, $admin['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Set session variables for the logged-in admin
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Regenerate CSRF token after login
                unset($_SESSION['csrf_token']);
                generateCsrfToken();
                
                header("Location: " . url('admin/dashboard'));
                exit();
            } else {
                $errors['login'] = "Invalid email or password.";
            }
        }
    }

    // Include the login form view and pass the errors array
    include __DIR__ . '/../views/admin/login.php';
}

// Admin Registration
public function register()
{
    header('X-Robots-Tag: noindex, nofollow');
    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize inputs
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        // Validate inputs
        if (empty($username)) {
            $errors['username'] = "Username is required.";
        } elseif (strlen($username) < 3) {
            $errors['username'] = "Username must be at least 3 characters.";
        }

        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        } elseif ($this->adminModel->checkEmailExists($email)) {
            $errors['email'] = "Email already registered.";
        }

        if (empty($password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters.";
        }

        if (empty($confirmPassword)) {
            $errors['confirm_password'] = "Please confirm your password.";
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        // If no errors, create the admin
        if (empty($errors)) {
            $adminId = $this->adminModel->createAdmin($username, $email, $password);
            
            if ($adminId) {
                $success = true;
                // Optionally auto-login after registration
                // session_regenerate_id(true);
                // $_SESSION['admin_id'] = $adminId;
                // $_SESSION['admin_username'] = $username;
                // header("Location: /admin/dashboard");
                // exit();
            } else {
                $errors['general'] = "Registration failed. Please try again.";
            }
        }
    }

    // Include the registration form view
    include __DIR__ . '/../views/admin/register.php';
}

  public function dashboard()
  {
      header('X-Robots-Tag: noindex, nofollow');
    // Check if the admin is logged in
    if (!isset($_SESSION['admin_id'])) {
      header('Location: /admin/login');
      exit();
    }

    // Greet admin by name
    $adminUsername = $_SESSION['admin_username'];

    // Fetch stats
    $totalUsers = $this->userModel->getTotalUsersCount();
    $totalQuotes = $this->quoteModel->getTotalQuoteCount();
    $totalBlogs = count($this->blogModel->getBlogsByAdminId($_SESSION['admin_id'])); // Counts admin's blogs
    
    // Fetch blogs created by the admin
    $blogs = $this->blogModel->getBlogsByAdminId($_SESSION['admin_id']);

    // Load the dashboard view
    require_once __DIR__ . '/../views/admin/dashboard.php';
  }

  public function analytics()
  {
      header('X-Robots-Tag: noindex, nofollow');
    // Check if the admin is logged in
    if (!isset($_SESSION['admin_id'])) {
      header('Location: /admin/login');
      exit();
    }

     // Get all users from the UserModel
    $users = $this->userModel->getAllUsers(); 

    // Count the total number of users
    $totalUsers = count($users);

    // Pagination setup
    $limit = 50; // Show 50 users per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page); // Ensure page is at least 1
    $offset = ($page - 1) * $limit;
    
    // Search
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    if ($search) {
        $usersWithCount = $this->userModel->searchUsersWithQuoteCountPaginated($search, $limit, $offset);
        $totalUsersQuotesCount = $this->userModel->getSearchUsersCount($search);
    } else {
        $usersWithCount = $this->userModel->getAllUsersWithQuoteCountPaginated($limit, $offset);
        $totalUsersQuotesCount = $this->userModel->getTotalUsersCount();
    }
        
    $totalPages = ceil($totalUsersQuotesCount / $limit);
    
    $quotesT = $this->quoteModel->getTotalQuoteCount(); 
    
    require_once __DIR__ . '/../views/admin/analytics.php';
  }
  
  
  public function createBlog()
  {
      header('X-Robots-Tag: noindex, nofollow');
    if (isset($_SESSION['admin_id'])) {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF validation
        if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
          header("Location: " . url('admin/create-blog?error=' . urlencode('Invalid security token')));
          exit();
        }
        
        $title = $_POST['title'];
        $content = trim(htmlspecialchars($_POST['editor1']));
        $this->blogModel->createBlog($_SESSION['admin_id'], $title, $content);
        header("Location: " . url('admin/dashboard?success=' . urlencode('Blog created successfully!')));
        exit();
      } else {
        require_once __DIR__ . '/../views/admin/create-blog.php';
      }
    } else {
      header("Location: " . url('admin/login'));
      exit();
    }
  }
  public function editBlog($id)
  {
      header('X-Robots-Tag: noindex, nofollow');
    if (isset($_SESSION['admin_id'])) {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF validation
        if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
          header("Location: " . url('admin/edit-blog/' . $id . '?error=' . urlencode('Invalid security token')));
          exit();
        }
        
        $title = $_POST['title'];
        $content = $_POST['editor1'];
        $this->blogModel->updateBlog($id, $title, $content);
        header("Location: " . url('admin/dashboard?success=' . urlencode('Blog updated successfully!')));
        exit();
      } else {
        $blog = $this->blogModel->getBlogById($id);
        require_once __DIR__ . '/../views/admin/edit-blog.php';
      }
    } else {
      header("Location: " . url('admin/login'));
      exit();
    }
  }
  public function deleteBlog($id)
  {
      header('X-Robots-Tag: noindex, nofollow');
    if (isset($_SESSION['admin_id'])) {
      $this->blogModel->deleteBlog($id);
      header("Location: " . url('admin/dashboard?success=' . urlencode('Blog deleted successfully!')));
      exit();
    } else {
      header("Location: " . url('admin/login'));
      exit();
    }
  }
  
  
   // 1. Display all quotes (Admin view) with pagination and search
    public function listAllQuotes()
    {
        header('X-Robots-Tag: noindex, nofollow');
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit();
        }

        // Pagination setup
        $limit = 50; // Show 50 quotes per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Ensure page is at least 1
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search) {
            $quotes = $this->quoteModel->searchQuotesForAdminPaginated($search, $limit, $offset);
            $totalQuotes = $this->quoteModel->getSearchQuotesCount($search);
        } else {
            $quotes = $this->quoteModel->getAllQuotesForAdminPaginated($limit, $offset);
            $totalQuotes = $this->quoteModel->getTotalQuoteCount();
        }
        
        $totalPages = ceil($totalQuotes / $limit);

        require_once __DIR__ . '/../views/admin/quotes/index.php';
    }

 
public function editQuoteAdmin($id) {
    
    header('X-Robots-Tag: noindex, nofollow');
    
    // Check if the admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login');
        exit();
    }

    // Fetch the quote by its ID
    $quote = $this->quoteModel->getQuoteByIdAdmin($id);

    if (!$quote) {
        // If the quote doesn't exist, show a 404 error page
        require_once __DIR__ . '/../views/pages/404-page.php';
        exit();
    }

    // Fetch the categories for the dropdown
    $categories = $this->quoteModel->getAllCategories(); // Fetch all categories
    // var_dump($categories);
    
    // If POST request, process form data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
      header("Location: " . url('admin/editquote/' . $id . '?error=' . urlencode('Invalid security token')));
      exit();
    }
    
    $author = trim($_POST['author']);
    $quoteText = trim($_POST['quote_text']);
    $categoryId = intval($_POST['category_id']); // Get the selected category ID

    // Update the quote in the database
    $this->quoteModel->updateQuoteAdmin($id, $author, $quoteText, $categoryId); // Keep $id unchanged

    // Redirect to the all quotes page
    header("Location: " . url('admin/allquotes?success=' . urlencode('Quote updated successfully!')));
    exit();
}


    // Load the edit quote view with the quote details
    require_once __DIR__ . '/../views/admin/quotes/edit.php';
}

// Delete Quote (Admin)
public function deleteQuoteAdmin($id)
{
    header('X-Robots-Tag: noindex, nofollow');
    
    if (isset($_SESSION['admin_id'])) {
        if ($this->quoteModel->deleteQuoteAdmin($id)) {
            header("Location: " . url('admin/allquotes?deleted=success'));
            exit();
        } else {
            require_once __DIR__ . '/../views/pages/404-page.php';
        }
    } else {
        header("Location: /admin/login");
        exit();
    }
}
    
  // Admin Logout
  public function logout()
  {
    header('X-Robots-Tag: noindex, nofollow');
      
      
    unset($_SESSION['admin_id']);
    session_destroy();
    header("Location: " . url('admin/login'));
    exit();
  }
}
