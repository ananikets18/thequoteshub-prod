<?php
class QuoteModel
{

  private $conn;

  private $bannedWords = ['sex', 'fuck', 'ass']; // Add more words as needed

  public function __construct($conn)
  {
    if (!$conn) {
      error_log("QuoteModel: Database connection is null."); // Debugging log
      throw new Exception("No database connection.");
    }
    $this->conn = $conn;
    error_log("QuoteModel: Database connection established.");
  }

  private function containsBannedWords($input)
  {
    foreach ($this->bannedWords as $word) {
      if (stripos($input, $word) !== false) { // Case-insensitive search
        return true;
      }
    }
    return false;
  }
  
    // Fetch all quotes for a specific user
    public function getUserQuotes($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM quotes WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Search Quotes
            public function searchQuotes($query)
            {
                // Query for searching quotes
                $quoteSql = "
                    SELECT 'quote' AS type, quotes.id, quotes.quote_text AS content, authors.author_name
                    FROM quotes
                    LEFT JOIN authors ON quotes.author_id = authors.id
                    WHERE quotes.quote_text LIKE ?
                    LIMIT 10
                ";
            
                // Query for searching authors
                $authorSql = "
                    SELECT 'author' AS type, authors.id, authors.author_name AS content
                    FROM authors
                    WHERE authors.author_name LIKE ?
                ";
            
                // Query for searching users
                $userSql = "
                    SELECT 'user' AS type, users.id, users.username AS user_name, users.name AS content
                    FROM users
                    WHERE users.name LIKE ?
                ";
            
                $likeQuery = '%' . $query . '%';
            
                // Search for quotes
                $stmt = $this->conn->prepare($quoteSql);
                $stmt->bind_param('s', $likeQuery);
                $stmt->execute();
                $quoteResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
                // Search for authors
                $stmt = $this->conn->prepare($authorSql);
                $stmt->bind_param('s', $likeQuery);
                $stmt->execute();
                $authorResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
                // Search for users
                $stmt = $this->conn->prepare($userSql);
                $stmt->bind_param('s', $likeQuery);
                $stmt->execute();
                $userResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
                // Combine all results
                return array_merge($quoteResults, $authorResults, $userResults);
            }


       public function updateAuthorInQuotes($authorId, $authorName)
    {
        // Prepare and execute the update query
        $stmt = $this->conn->prepare("
            UPDATE quotes 
            SET author_id = ?, update_author_info = TRUE 
            WHERE author_name = ?
        ");
        $stmt->bind_param("is", $authorId, $authorName);

        return $stmt->execute(); // Return true if update is successful, false otherwise
    }
        
    public function getFollowingQuotes($userId, $limit, $offset)
    {
        // SQL query to fetch quotes from the users that the given user is following
        $sql = "
            SELECT q.id, q.quote_text, q.author_name, q.created_at, u.name AS user_name, u.username AS user__name, u.user_img AS user_img
            FROM quotes q
            INNER JOIN users u ON q.user_id = u.id
            WHERE q.user_id IN (
                SELECT followed_id FROM follows WHERE follower_id = ?
            )
            ORDER BY q.created_at DESC
            LIMIT ? OFFSET ?"; // Ensure LIMIT and OFFSET are used here
    
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
    
        // Bind the user ID, limit, and offset parameters
        $stmt->bind_param("iii", $userId, $limit, $offset);
    
        // Execute the statement
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
    
        // Fetch all the quotes and return them as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    
    public function getQuotesWithPagination($limit, $offset)
  {
    $sql = "SELECT quotes.*, 
                   users.name AS user_name, 
                   users.username AS user__name,  
                   users.user_img AS user_img,
                   users.status AS user_status 
            FROM quotes
            INNER JOIN users ON quotes.user_id = users.id
            ORDER BY quotes.created_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $this->conn->prepare($sql);

    if ($stmt === false) {
      error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
      return []; // Return an empty array to handle gracefully in the UI
    }

    if (!$stmt->bind_param('ii', $limit, $offset)) {
      error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
      return [];
    }

    if (!$stmt->execute()) {
      error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
      return [];
    }

    $result = $stmt->get_result();
    if ($result === false) {
      error_log("Getting result set failed: (" . $stmt->errno . ") " . $stmt->error);
      return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getQuotesCount()
  {
    $sql = "SELECT COUNT(*) as total FROM quotes";
    $result = mysqli_query($this->conn, $sql);

    if (!$result) {
      error_log("Query failed: (" . $this->conn->errno . ") " . $this->conn->error);
      return 0; // Safe default
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
  }
  
    public function incrementViewCount($quoteId)
  {
    // Prepare the statement
    $stmt = $this->conn->prepare("UPDATE quotes SET view_count = view_count + 1 WHERE id = ?");

    // Bind the parameter (make sure to use the correct type, 'i' for integer)
    $stmt->bind_param("i", $quoteId);

    // Execute the statement
    $stmt->execute();

    // Optionally check for errors
    if ($stmt->error) {
      error_log("MySQL Error: " . $stmt->error); // Log the error if needed
    }

    // Close the statement
    $stmt->close();
  }

  public function getTotalViewsByUserId($userId)
  {
    $stmt = $this->conn->prepare("SELECT SUM(view_count) AS total_views FROM quotes WHERE user_id = ?");
    $stmt->bind_param("i", $userId); // Bind the user_id parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['total_views'] ? $row['total_views'] : 0; // Return total views, default to 0 if null
  }
  
    public function getOlderQuotes()
    {
    try {
        // Prepare the SQL statement to get all quotes with user information, ordered by created_at in ascending order
        $sql = "SELECT quotes.*, users.name AS user_name, users.username AS user__name, users.user_img AS user_img
                FROM quotes
                INNER JOIN users ON quotes.user_id = users.id
                ORDER BY quotes.created_at ASC"; // Order by created_at ascending for older quotes

        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            // Log the preparation error
            error_log("SQL error during preparation: " . $this->conn->error);
            return []; // Return an empty array if preparation fails
        }

        // Execute the statement
        $success = $stmt->execute();

        if (!$success) {
            // Log execution error
            error_log("Execution failed: " . $stmt->error);
            return []; // Return an empty array if execution fails
        }

        // Get the result
        $result = $stmt->get_result();

        // Fetch as associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        // Log any exceptions that occur
        error_log("Exception caught: " . $e->getMessage());
        return []; // Return an empty array in case of an exception
    }
}

public function getUserLikedQuotes($userId)
{
    $stmt = $this->conn->prepare("SELECT q.id, q.quote_text, q.author_name FROM quotes q INNER JOIN likes l ON q.id = l.quote_id WHERE l.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function getUserSavedQuotes($userId)
{
    $stmt = $this->conn->prepare("SELECT q.id, q.quote_text, q.author_name FROM quotes q INNER JOIN saves s ON q.id = s.quote_id WHERE s.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


public function markAsEdited($quoteId) {
        $stmt = $this->conn->prepare("UPDATE quotes SET is_edited = 1 WHERE id = ?");
        $stmt->bind_param("i", $quoteId); // "i" for integer
        return $stmt->execute();
    }
  
   public function getAllQuotesForAdmin()
    {
        // Update the SQL query to join with users and categories tables
        $sql = "SELECT 
                quotes.id,
                quotes.quote_text,
                quotes.category_id,
                quotes.user_id,
                users.name AS user_name,
                quote_categories.category_name,
                quotes.is_edited_user,  -- Include user edited flag
                quotes.is_edited_admin   -- Include admin edited flag
            FROM quotes
            INNER JOIN users ON quotes.user_id = users.id
            INNER JOIN quote_categories ON quotes.category_id = quote_categories.id
            ORDER BY quotes.created_at DESC";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

   // Get all quotes for admin with pagination
   public function getAllQuotesForAdminPaginated($limit, $offset)
    {
        $sql = "SELECT 
                quotes.id,
                quotes.quote_text,
                quotes.author_name,
                quotes.category_id,
                quotes.user_id,
                quotes.created_at,
                users.name AS user_name,
                quote_categories.category_name,
                quotes.is_edited_user,
                quotes.is_edited_admin
            FROM quotes
            INNER JOIN users ON quotes.user_id = users.id
            INNER JOIN quote_categories ON quotes.category_id = quote_categories.id
            ORDER BY quotes.created_at DESC
            LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param('ii', $limit, $offset);
        
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Search quotes for admin with pagination
    public function searchQuotesForAdminPaginated($search, $limit, $offset)
    {
        $searchTerm = "%" . $search . "%";
        $sql = "SELECT 
                quotes.id,
                quotes.quote_text,
                quotes.author_name,
                quotes.category_id,
                quotes.user_id,
                quotes.created_at,
                users.name AS user_name,
                quote_categories.category_name,
                quotes.is_edited_user,
                quotes.is_edited_admin
            FROM quotes
            INNER JOIN users ON quotes.user_id = users.id
            INNER JOIN quote_categories ON quotes.category_id = quote_categories.id
            WHERE quotes.quote_text LIKE ? 
               OR quotes.author_name LIKE ? 
               OR users.name LIKE ? 
               OR quote_categories.category_name LIKE ?
            ORDER BY quotes.created_at DESC
            LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get count of search results
    public function getSearchQuotesCount($search)
    {
        $searchTerm = "%" . $search . "%";
        $sql = "SELECT COUNT(*) as total
            FROM quotes
            INNER JOIN users ON quotes.user_id = users.id
            INNER JOIN quote_categories ON quotes.category_id = quote_categories.id
            WHERE quotes.quote_text LIKE ? 
               OR quotes.author_name LIKE ? 
               OR users.name LIKE ? 
               OR quote_categories.category_name LIKE ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    public function getRandomQuote()
      {
        $query = "SELECT * FROM quotes ORDER BY RAND() LIMIT 1";
        $result = $this->conn->query($query);
    
        if ($result) {
          return $result->fetch_assoc();
        } else {
          die("Query failed: " . $this->conn->error);
        }
      }
    
    
      public function getQuoteAuthorId($quote_id)
  {
    $stmt = $this->conn->prepare("SELECT user_id FROM quotes WHERE id = ?");
    $stmt->bind_param("i", $quote_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      return $result->fetch_assoc()['user_id'];
    }
    return null; // Or handle accordingly
  }
  
  
  
     public function getAllCategories()
      {
        $sql = "SELECT id, category_name FROM quote_categories ORDER BY category_name ASC";
        $result = mysqli_query($this->conn, $sql);
        $categories = [];
    
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
          }
        }
    
        return $categories;
      }
  
    public function getTopUsers()
  {
    $query = "
        SELECT 
            u.id AS user_id, 
            u.name, 
            u.username, 
            u.user_img, 
            (SELECT COUNT(q.id) FROM quotes q WHERE q.user_id = u.id) AS total_quotes,
            (SELECT COUNT(l.id) FROM likes l WHERE l.user_id = u.id) AS total_likes,
            (SELECT COUNT(s.id) FROM saves s WHERE s.user_id = u.id) AS total_saves
        FROM users u
        ORDER BY total_quotes DESC, total_likes DESC, total_saves DESC
        LIMIT 5;"; // Limit to top 10 users

    $result = $this->conn->query($query);


    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function createCategory($categoryName)
  {
    if ($this->containsBannedWords($categoryName)) {
      throw new Exception("Category name contains inappropriate language.");
    }

    try {
      // Convert the category name to lowercase for case-insensitive comparison
      $stmt = $this->conn->prepare("SELECT category_name FROM quote_categories WHERE LOWER(category_name) = LOWER(?)");
      $stmt->bind_param("s", $categoryName);
      $stmt->execute();
      $result = $stmt->get_result();

      // If a category with the same name exists, throw an exception
      if ($result->num_rows > 0) {
        throw new Exception("Category name '" . htmlspecialchars($categoryName) . "' already exists. Please create another category or choose an existing one.");
      }

      // Insert the new category if no duplicate found
      $stmt = $this->conn->prepare("INSERT INTO quote_categories (category_name) VALUES (?)");
      $stmt->bind_param("s", $categoryName);
      $stmt->execute();

      return $stmt->insert_id;
    } catch (Exception $e) {
      throw new Exception("Error creating category: " . $e->getMessage());
    }
  }


public function getQuoteById($id)
{
    try {
        // Prepare the SQL statement
        $sql = "SELECT quotes.id, quotes.quote_text, quotes.author_name, quotes.created_at, quotes.user_id,
                       users.name AS user_name, users.username AS user__name, 
                       users.user_img AS user__img, users.status AS user_status,
                       quote_categories.id AS category_id, quote_categories.category_name,
                       (SELECT COUNT(*) FROM quotes WHERE user_id = users.id) AS user_quote_count
                FROM quotes
                INNER JOIN users ON quotes.user_id = users.id
                INNER JOIN quote_categories ON quotes.category_id = quote_categories.id
                WHERE quotes.id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare SQL statement: ' . mysqli_error($this->conn));
        }

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, 'i', $id);

        // Execute the statement
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Execution failed: ' . mysqli_stmt_error($stmt));
        }

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    } catch (Exception $e) {
        // Log the error (you can replace this with a logging mechanism)
        error_log($e->getMessage());

        // Return null or an error message to the calling function
        return null; // or return ['error' => 'An error occurred while fetching the quote.'];
    }
}


public function getQuoteByIdAdmin($id) {
    $sql = "SELECT 
                quotes.id, 
                quotes.quote_text, 
                quotes.author_name, 
                quotes.created_at, 
                quotes.category_id, 
                quote_categories.category_name
            FROM 
                quotes
            INNER JOIN 
                quote_categories ON quotes.category_id = quote_categories.id
            WHERE 
                quotes.id = ?";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($this->conn, $sql);

    // Bind the ID as an integer
    mysqli_stmt_bind_param($stmt, 'i', $id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Check if the result is valid and fetch the data as an associative array
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}




  public function getRandomQuotes($limit = 6)
  {
    $query = "SELECT * FROM quotes ORDER BY RAND() LIMIT ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function addQuote($authorName, $quoteText,  $categoryId, $userId)
  {

    if ($this->containsBannedWords($quoteText)) {
        throw new Exception("Quote contains inappropriate language.");
    }

    $stmt = $this->conn->prepare("INSERT INTO quotes (author_name, quote_text, category_id, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $authorName, $quoteText, $categoryId, $userId);
    
    $success = $stmt->execute();
    error_log("Quote Creation - User ID: $userId, Success: " . ($success ? 'Yes' : 'No'));
    
    return $success;
  }

     public function updateQuote($quoteId, $author, $quoteText)
        {
            // Check for banned words in the quote text
            if ($this->containsBannedWords($quoteText)) {
                throw new Exception("Quote contains inappropriate language.");
            }
        
            // SQL statement for updating the quote
            $sql = "UPDATE quotes SET author_name = ?, quote_text = ?, is_edited_user = 1, is_edited_admin = 0 WHERE id = ?";
        
            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);
        
            if ($stmt === false) {
                throw new Exception("Failed to prepare SQL statement: " . $this->conn->error);
            }
        
            // Bind parameters
            $stmt->bind_param("ssi", $author, $quoteText, $quoteId);
        
            // Execute the statement
            $success = $stmt->execute();
        
            if (!$success) {
                throw new Exception("Failed to execute SQL statement: " . $stmt->error);
            }
        
            return $success;
        }

public function updateQuoteAdmin($quoteId, $author, $quoteText, $categoryId)
{
    // SQL statement for updating the quote
    // $sql = "UPDATE quotes SET author_name = ?, quote_text = ?, category_id = ? WHERE id = ?";
     $sql = "UPDATE quotes SET author_name = ?, quote_text = ?, category_id = ?,  is_edited_admin = 1, is_edited_user = 0  WHERE id = ?";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Failed to prepare SQL statement: " . $this->conn->error);
    }

    // Bind parameters: "ssi" for string, string, integer (for category_id), integer (for quoteId)
    $stmt->bind_param("ssii", $author, $quoteText, $categoryId, $quoteId);

    // Execute the statement
    $success = $stmt->execute();

    if (!$success) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    return $success;
}

public function getTotalQuoteCount()
{
    // SQL query to count the total number of quotes
    $query = "SELECT COUNT(*) as total_quotes FROM quotes";
    
    // Prepare the statement
    $stmt = $this->conn->prepare($query);

    if ($stmt === false) {
        // Log error and return 0 if the query preparation fails
        error_log("SQL error: " . $this->conn->error);
        return 0; // Return 0 in case of error
    }

    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result()->fetch_assoc();
    
    // Return the total number of quotes
    return $result['total_quotes'];
}


  public function getQuotesByUserId($userId)
  {
    $stmt = $this->conn->prepare("SELECT * FROM quotes WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging output
    error_log("SQL Query: SELECT * FROM quotes WHERE user_id = $userId");
    $quotes = $result->fetch_all(MYSQLI_ASSOC);
    error_log("Result: " . print_r($quotes, true));

    return $quotes;
  }

  public function getUserQuoteCount($userId)
  {
    $query = "SELECT COUNT(*) as quote_count FROM quotes WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['quote_count'];
  }
public function getUniqueAuthors() {
    // Query to fetch authors starting with A-Z, appending others at the end
    $query = "
        SELECT DISTINCT q.author_name, a.author_img
    FROM quotes q
    JOIN authors a ON q.author_name = a.author_name
    WHERE q.author_name IS NOT NULL
      AND q.author_name != ''
    ORDER BY 
      CASE
        WHEN q.author_name REGEXP '^[A-Za-z]' THEN 1 -- A-Z first
        ELSE 2 -- Others after
      END,
      q.author_name ASC"; 
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


public function getQuotesByAuthor($authorName) {
    // Trim input to remove leading and trailing whitespace
    $authorName = trim($authorName);

    // SQL query using LOWER() to handle case-insensitivity
    $query = "SELECT id, quote_text, author_name 
              FROM quotes 
              WHERE LOWER(TRIM(author_name)) = LOWER(TRIM(?)) 
              ORDER BY created_at DESC";
    
    // Prepare the SQL statement
    $stmt = $this->conn->prepare($query);

    // Bind the parameter
    $stmt->bind_param("s", $authorName);

    // Execute the statement
    if (!$stmt->execute()) {
        // Log error if query fails
        echo "Query execution failed: " . $stmt->error;
        return [];
    }

    // Fetch results
    $result = $stmt->get_result();

    // Handle no results case
    // if ($result->num_rows === 0) {
    //     echo "No quotes found for author: " . htmlspecialchars($authorName);
    // }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Delete quote (Admin only)
public function deleteQuoteAdmin($quoteId)
{
    // First, delete related records (likes, saves, etc.) to maintain referential integrity
    // Delete likes
    $stmt = $this->conn->prepare("DELETE FROM likes WHERE quote_id = ?");
    $stmt->bind_param("i", $quoteId);
    $stmt->execute();
    
    // Delete saves
    $stmt = $this->conn->prepare("DELETE FROM saves WHERE quote_id = ?");
    $stmt->bind_param("i", $quoteId);
    $stmt->execute();
    
    // Now delete the quote itself
    $stmt = $this->conn->prepare("DELETE FROM quotes WHERE id = ?");
    $stmt->bind_param("i", $quoteId);
    
    if ($stmt->execute()) {
        return true;
    }
    
    error_log("Failed to delete quote ID: $quoteId - " . $stmt->error);
    return false;
}



}