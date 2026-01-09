<?php

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AuthorModel.php';
require_once __DIR__ . '/../models/LikeModel.php';
require_once __DIR__ . '/../models/SaveModel.php';  
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class QuoteController
{
  private $quoteModel;
  private $authorModel;
  private $userModel;
  private $likeModel;
  private $saveModel;
  private $notificationModel;
  private $categoryModel;

  private $bannedWords = ['sex', 'fuck', 'dick']; // Add more words as needed

  private function containsBannedWords($input)
  {
    foreach ($this->bannedWords as $word) {
      if (stripos($input, $word) !== false) { // Case-insensitive search
        return true;
      }
    }
    return false;
  }

    public function __construct($conn)
    {

    $this->likeModel = new LikeModel($conn);
    $this->saveModel = new SaveModel($conn);
    $this->userModel = new UserModel($conn);
    $this->authorModel = new AuthorModel($conn);
    $this->notificationModel = new notificationModel($conn);
    $this->categoryModel = new CategoryModel($conn);
    
    
   if (!$conn) {
      error_log("QuoteController: Database connection is null."); // Debugging log
      throw new Exception("No database connection.");
    }
    $this->quoteModel = new QuoteModel($conn);
    error_log("QuoteController: Successfully passed database connection to QuoteModel.");
  }

public function index()
{
    // Default initialization of the data arrays
    $isLiked = [];
    $likeCounts = [];
    $isSaved = [];
    $saveCounts = [];

    // Pagination setup
    $limit = 20; // Limit of quotes per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    // var_dump($page);
    $offset = ($page - 1) * $limit;

    // Check if the tab is 'following', otherwise default to 'discover'
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'discover';

    // Fetch quotes based on the selected tab
    if ($tab === 'following' && isset($_SESSION['user_id'])) {
        // Fetch quotes from followed users
        $userId = $_SESSION['user_id'];
        $Latestquotes = $this->quoteModel->getFollowingQuotes($userId, $limit, $offset);
    } else {
        // Fetch the latest quotes for 'discover' tab
        $Latestquotes = $this->quoteModel->getQuotesWithPagination($limit, $offset);
    }

    if ($Latestquotes === false) {
        error_log("Error fetching paginated quotes.");
        $Latestquotes = []; // Fallback to empty array on error
    }

    // Fetch total quotes count for pagination control
    $totalQuotes = $this->quoteModel->getQuotesCount();
    if ($totalQuotes === false) {
        error_log("Error fetching total quote count.");
        $totalQuotes = 0;
    }
    $totalPages = ceil($totalQuotes / $limit);
    // var_dump($totalPages);

    // Fetch users with most quotes, likes, and saves
    $topUsers = $this->quoteModel->getTopUsers();
    if ($topUsers === false) {
        error_log("Error fetching top users.");
        $topUsers = []; // Fallback to empty array on error
    }

    // Calculate isLiked, isSaved, and count data
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Fetch user details and counts
        $userDetails = $this->userModel->getUserDetails($userId);
        $quoteCount = $this->quoteModel->getUserQuoteCount($userId);
        $likeCount = $this->likeModel->getUserLikeCount($userId);
        $saveCount = $this->saveModel->getUserSaveCount($userId);

        foreach ($Latestquotes as $quote) {
            $isLiked[$quote['id']] = $this->likeModel->isLiked($userId, $quote['id']);
            $likeCounts[$quote['id']] = $this->likeModel->getLikeCount($quote['id']);
            $isSaved[$quote['id']] = $this->saveModel->isSaved($userId, $quote['id']);
            $saveCounts[$quote['id']] = $this->saveModel->getSaveCount($quote['id']);
        }
    } else {
        foreach ($Latestquotes as $quote) {
            $isLiked[$quote['id']] = false;
            $likeCounts[$quote['id']] = $this->likeModel->getLikeCount($quote['id']);
            $isSaved[$quote['id']] = false;
            $saveCounts[$quote['id']] = $this->saveModel->getSaveCount($quote['id']);
        }
    }

    // Fetch additional data for the "quote of the day"
    $quoteOfTheDay = $this->quoteModel->getRandomQuote();
    if ($quoteOfTheDay === false) {
        error_log("Error fetching quote of the day.");
        $quoteOfTheDay = null; // Fallback on error
    }

    // Fetch categories for the sidebar
    $categories = $this->categoryModel->getCategories();
    if ($categories === false) {
        error_log("Error fetching categories.");
        $categories = []; // Fallback on error
    }

    // Set isLoggedIn flag for views
    $isLoggedIn = isset($_SESSION['user_id']);

    // Set baseUrl for views
    $baseUrl = BASE_URL . '/';

    // Pass data to the view
    require_once __DIR__ . '/../views/quotes/index.php';
}


//     public function index()
//     {
//     // Default initialization of the data arrays
//     $isLiked = [];
//     $likeCounts = [];
//     $isSaved = [];
//     $saveCounts = [];
    
  

//     // Pagination setup
//     $limit = 20; // Limit of quotes per page
//     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//     $offset = ($page - 1) * $limit;

//     // Fetch the quotes with pagination
//     $Latestquotes = $this->quoteModel->getQuotesWithPagination($limit, $offset);
//     if ($Latestquotes === false) {
//         error_log("Error fetching paginated quotes.");
//         $Latestquotes = []; // Fallback to empty array on error
//     }

//     // Fetch total quotes count for pagination control
//     $totalQuotes = $this->quoteModel->getQuotesCount();
//     if ($totalQuotes === false) {
//         error_log("Error fetching total quote count.");
//         $totalQuotes = 0;
//     }
//     $totalPages = ceil($totalQuotes / $limit);

//     // Fetch users with most quotes, likes, and saves
//     $topUsers = $this->quoteModel->getTopUsers();
//     // var_dump($topUsers);
    
//     if ($topUsers === false) {
//         error_log("Error fetching top users.");
//         $topUsers = []; // Fallback to empty array on error
//     }

//     // Calculate isLiked, isSaved, and count data
//     if (isset($_SESSION['user_id'])) {
//         $userId = $_SESSION['user_id'];

//         // Fetch user details and counts
//         $userDetails = $this->userModel->getUserDetails($userId);
//         $quoteCount = $this->quoteModel->getUserQuoteCount($userId);
//         $likeCount = $this->likeModel->getUserLikeCount($userId);
//         $saveCount = $this->saveModel->getUserSaveCount($userId);

//         foreach ($Latestquotes as $quote) {
//             $isLiked[$quote['id']] = $this->likeModel->isLiked($userId, $quote['id']);
//             $likeCounts[$quote['id']] = $this->likeModel->getLikeCount($quote['id']);
//             $isSaved[$quote['id']] = $this->saveModel->isSaved($userId, $quote['id']);
//             $saveCounts[$quote['id']] = $this->saveModel->getSaveCount($quote['id']);
//         }
//     } else {
//         foreach ($Latestquotes as $quote) {
//             $isLiked[$quote['id']] = false;
//             $likeCounts[$quote['id']] = $this->likeModel->getLikeCount($quote['id']);
//             $isSaved[$quote['id']] = false;
//             $saveCounts[$quote['id']] = $this->saveModel->getSaveCount($quote['id']);
//         }
//     }

//     // Fetch additional data
//     $quoteOfTheDay = $this->quoteModel->getRandomQuote();
//     if ($quoteOfTheDay === false) {
//         error_log("Error fetching quote of the day.");
//         $quoteOfTheDay = null; // Fallback on error
//     }

//     $categories = $this->categoryModel->getCategories();
//     if ($categories === false) {
//         error_log("Error fetching categories.");
//         $categories = []; // Fallback on error
//     }

//     // Pass data to the view
//     require_once __DIR__ . '/../views/quotes/index.php';
// }

    public function createQuote()
    {
  if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit();
  }

  $message = '';
  $errors = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authorName = trim(htmlspecialchars($_POST['author_name']));
    $quoteText = trim(htmlspecialchars($_POST['quote_text']));
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $newCategory = trim(htmlspecialchars($_POST['new_category']));
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    // Filter inappropriate words
    if ($this->containsBannedWords($authorName)) {
      $errors[] = "Author name contains inappropriate language.";
    }
    if ($this->containsBannedWords($quoteText)) {
      $errors[] = "Quote text contains inappropriate language.";
    }
    if ($this->containsBannedWords($newCategory)) {
      $errors[] = "New category name contains inappropriate language.";
    }

    // If new category is provided, insert it into the database
    if (!empty($newCategory)) {
      if (empty($errors)) { // Only proceed if there are no errors
        try {
          $categoryId = $this->quoteModel->createCategory($newCategory);
        } catch (Exception $e) {
          $errors[] = $e->getMessage(); // Capture the error and show it on the form
        }
      }
    }

    // Ensure categoryId is null if no valid category is selected or created
    // if (empty($categoryId)) {
    //   $categoryId = null;
    // }
    
       // Ensure a category is selected
    if (empty($categoryId)) {
      $errors[] = "Please select a valid category.";
    }




  // If no errors, save the quote
if (empty($errors)) {
    // Save the quote and get the ID of the newly created quote
    $quoteId = $this->quoteModel->addQuote($authorName, $quoteText, $categoryId, $userId);

    if ($quoteId) {
        $message = "Quote created successfully.";
        
        // Award points for creating a quote
        $this->userModel->updatePoints($userId, 10); // Award 10 points for creating

        // Initialize badge notification variable
        $badgeNotification = '';

        // Fetch the user's total quote count
        $quoteCount = $this->quoteModel->getUserQuoteCount($userId);

        // Assign badges based on quote count and notify user
        if ($quoteCount >= 50) {
            $badgeName = '50 Quotes - 50 quotes published';
            if (!$this->userModel->hasBadge($userId, $badgeName)) {
                $this->userModel->awardBadge($userId, $badgeName);
                $badgeNotification = "Congratulations! You've earned the '$badgeName' badge.";
            }
        } elseif ($quoteCount >= 10) {
            $badgeName = '10 Quotes - 10 quotes published';
            if (!$this->userModel->hasBadge($userId, $badgeName)) {
                $this->userModel->awardBadge($userId, $badgeName);
                $badgeNotification = "Congratulations! You've earned the '$badgeName' badge.";
            }
        } elseif ($quoteCount == 1) {
            $badgeName = 'Debut Writer';
            if (!$this->userModel->hasBadge($userId, $badgeName)) {
                $this->userModel->awardBadge($userId, $badgeName);
                $badgeNotification = "Congratulations! You've earned the '$badgeName' badge.";
            }
        }

        // If a badge notification exists, set it as a session variable
        if (!empty($badgeNotification)) {
            $_SESSION['badgeNotification'] = $badgeNotification;
        }

        header("Location: /");
        exit();
    } else {
        $errors[] = "Failed to create quote. Please try again.";
    }
}

  }

  // Pass the errors and message to the view
  require_once __DIR__ . '/../views/quotes/create.php';
}

    public function view($id)
    {

    // Fetch the specific quote by its ID
    $quote = $this->quoteModel->getQuoteById($id);
    
       // Increment the view count for the quote
    if ($quote) {
      $this->quoteModel->incrementViewCount($id); // Add this line
    }
    

    // Fetch some random quotes
    $quotes = $this->quoteModel->getRandomQuotes();
    
    $randomFiveCategories = $this->categoryModel->getRandomFiveCategories();

    

    // Initialize like and save data
    $isLiked = false;
    $likeCount = 0;
    $isSaved = false;
    $saveCount = 0;

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Get like and save status and counts
        $isLiked = $this->likeModel->isLiked($userId, $id);
        $likeCount = $this->likeModel->getLikeCount($id);
        $isSaved = $this->saveModel->isSaved($userId, $id);
        $saveCount = $this->saveModel->getSaveCount($id);
    } else {
        // User is not logged in, so they can't like or save the quote
        $likeCount = $this->likeModel->getLikeCount($id);
        $saveCount = $this->saveModel->getSaveCount($id);
    }

    // Handle case where the quote does not exist
    if ($quote) {
        require_once __DIR__ . '/../views/quotes/view.php';
    } else {
        require_once __DIR__ . '/../views/pages/404-page.php';
    }
}

    // Like a quote
    public function toggleLike($quote_id)
    {
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $isLiked = $this->likeModel->isLiked($userId, $quote_id);
    
     // Get the author's user ID of the quote
    $quoteAuthorId = $this->quoteModel->getQuoteAuthorId($quote_id);

    if ($isLiked) {
      // Remove like
      $this->likeModel->removeLike($userId, $quote_id);
       // Fetch notification ID related to this like
      $notificationId = $this->notificationModel->getNotificationId($quoteAuthorId, $userId, $quote_id);

    
      
      $newLikeCount = $this->likeModel->getLikeCount($quote_id);
      echo json_encode([
        'success' => true,
        'liked' => false,
        'like_count' => $newLikeCount
      ]);
      exit;
    } else {
      // Add like
      $this->likeModel->addLike($userId, $quote_id);
      $newLikeCount = $this->likeModel->getLikeCount($quote_id);
      
        // Create a notification for the quote author only if the liker is not the author
      if ($userId !== $quoteAuthorId) {
        // Notify the author of the quote about the new like
        $this->notificationModel->createNotification($quoteAuthorId, $userId, $quote_id, 'like');

        // Award points for saving the quote
        $this->userModel->updatePoints($userId, 5); // Award 3 points for saving
      }
      echo json_encode([
        'success' => true,
        'liked' => true,
        'like_count' => $newLikeCount
      ]);
      exit;
    }
  }

    // Save a quote
    public function toggleSave($quote_id)
    {
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $isSaved = $this->saveModel->isSaved($userId, $quote_id);
    
    // Fetch the quote author
    $quoteAuthorId = $this->quoteModel->getQuoteAuthorId($quote_id);

    if ($isSaved) {
        // Remove save
        $this->saveModel->removeSave($userId, $quote_id);
        $newSaveCount = $this->saveModel->getSaveCount($quote_id);
        
        // Fetch notification ID related to this save
        $notificationId = $this->notificationModel->getNotificationId($quoteAuthorId, $userId, $quote_id);

   

        echo json_encode([
            'success' => true,
            'saved' => false,
            'save_count' => $newSaveCount
        ]);
    } else {
        // Add save
        $this->saveModel->addSave($userId, $quote_id);
        $newSaveCount = $this->saveModel->getSaveCount($quote_id);
        
        // Check if the user is saving their own quote
        if ($userId !== $quoteAuthorId) {
            // Create a notification for the quote author
            $this->notificationModel->createNotification($quoteAuthorId, $userId, $quote_id, 'save');

            // Award points for saving the quote
            $this->userModel->updatePoints($userId, 3); // Award 3 points for saving
        }

        echo json_encode([
            'success' => true,
            'saved' => true,
            'save_count' => $newSaveCount
        ]);
    }
}
  
    public function dashboard()
    {
          if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
          }
        
          $userId = $_SESSION['user_id'];
          
          // Fetch user's quotes
          $quotes = $this->quoteModel->getUserQuotes($userId);
        //   var_dump($quotes);
        
            // Get the total views, likes, and saves for the user's quotes
            $totalViews = $this->quoteModel->getTotalViewsByUserId($userId);
            $totalLikes = $this->likeModel->getTotalLikesByUserId($userId);
            $totalSaves = $this->saveModel->getTotalSavesByUserId($userId);
        
          // Count the quotes
          $quoteCount = count($quotes);
        
          // Fetch user badges
          $badges = $this->userModel->getUserBadges($userId);
        
          require_once __DIR__ . '/../views/users/dashboard.php';
        }

    public function editQuote($quoteId) {
    if (!isset($quoteId)) {
        echo "Quote ID is required.";
        return;
    }

    $quote = $this->quoteModel->getQuoteById($quoteId);

    if (!$quote) {
        echo "Quote not found.";
        require_once __DIR__ . '/../views/pages/404-page.php';
        return;
    }

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $author = trim(htmlspecialchars($_POST['author']));
        $quoteText = trim(htmlentities($_POST['quote_text']));

        if (empty($quoteText)) {
            $message = "Quote Text is required.";
        } else {
            // Proceed with updating the quote
            $updateSuccess = $this->quoteModel->updateQuote($quoteId, $author, $quoteText);

            if ($updateSuccess) {
                // Set the is_edited field to 1
                $this->quoteModel->markAsEdited($quoteId); // Function to update the is_edited field

                // Set the success message in the session
                $_SESSION['message'] = "Quote Edited successfully 🎉!";
            } else {
                $message = "Failed to update quote. Please try again.";
            }
        }
    }

    // Include the view to render the edit form with existing data
    require_once __DIR__ . '/../views/quotes/edit-quote.php';
}

public function listAuthors() {
    // Fetch authors with at least one quote, sorted A-Z
    $authors = $this->quoteModel->getUniqueAuthors();
    
    if (empty($authors)) {
        echo "No authors found with quotes.";
        require_once __DIR__ . '/../views/pages/404-page.php';
        return;
    }
    
    // Render the authors view
    require_once __DIR__ . '/../views/authors/index.php';
}

 public function viewAuthorQuotes($authorName) {

    // Fetch quotes for the author from the model
    $quotes = $this->quoteModel->getQuotesByAuthor($authorName);
    
    
    // Fetch author details (description, image, profession) from the authors table
    $authorDetails = $this->authorModel->getAuthorDetailsByName($authorName);


    // Render the quotes view for the author with additional author details
    require_once __DIR__ . '/../views/authors/author.php';
}


}
