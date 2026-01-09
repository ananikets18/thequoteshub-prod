<?php
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/AuthorModel.php';
require_once __DIR__ . '/../../config/utilis.php';

class AuthorController
{
       private $conn;
  private $quoteModel;
      private $authorModel;

  public function __construct($conn)
  {
   $this->conn = $conn;
        $this->authorModel = new AuthorModel($conn); // Initialize the AuthorModel
    $this->quoteModel = new quoteModel($conn);

  }
    public function listUnupdatedAuthors()
    {
        $authors = $this->authorModel->getUnupdatedAuthors();
        // print_r($authors);
        
        if (empty($authors)) {
            echo "No authors need updates.";
            require_once __DIR__ . '/../views/pages/404-page.php';
            return;
        }

        require_once __DIR__ . '/../views/authors/update-author-list.php';
    }
    
        // Display form for adding a new author
    public function addAuthor($authorName)
    {
        // $authorData = ['author_name' => $authorName, 'description' => '', 'profession' => ''];
        require_once __DIR__ . '/../views/authors/create-author.php';
    }


public function saveNewAuthor()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $authorName = $_POST['author_name'];
        $description = $_POST['description'];
        $profession = $_POST['profession'];
        $imagePath = null;

        // Handle image upload
        if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['author_image'];
            $targetDir = __DIR__ . "/../../public/uploads/authors_images/";

            // Generate a unique filename for the image
            $hash = md5(uniqid(rand(), true));
            $fileExtension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
            $newFilename = $hash . '.' . $fileExtension;
            $targetFile = $targetDir . $newFilename;

            // Validate image
            $check = getimagesize($image["tmp_name"]);
            if ($check === false) {
                echo json_encode(['status' => 'error', 'message' => 'File is not a valid image.']);
                return; // Exit after sending the response
            }

            // Check file size (limit: 2MB)
            if ($image["size"] > 2000000) {
                echo json_encode(['status' => 'error', 'message' => 'File is too large. Maximum size is 2MB.']);
                return;
            }

            // Validate file extension (only allow JPG, PNG, JPEG, GIF)
            if (!in_array($fileExtension, ["jpg", "png", "jpeg", "gif"])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Allowed formats: JPG, PNG, JPEG, GIF.']);
                return;
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                $imagePath = $newFilename;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload the image.']);
                return;
            }
        }

        // Prepare data for database insertion (include image path if uploaded)
        $authorData = [
            'author_name' => $authorName,
            'description' => $description,
            'profession' => $profession,
            'author_img' => $imagePath // Add image path to the data
        ];

        // Save the author data to the database
        $result = $this->authorModel->createAuthor($authorData);

        // Check if the author was created successfully
        if ($result) {
            // Get the newly created author's ID
            $authorId = $this->conn->insert_id;

            // Update the quotes table with the new author_id and set update_author_info to TRUE
            $updateResult = $this->quoteModel->updateAuthorInQuotes($authorId, $authorName);

            if ($updateResult) {
                echo json_encode(['status' => 'success', 'message' => 'Author added and quotes updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update quotes.']);
            }
            exit(); // Exit after sending the response
        } else {
            // Log the error if it fails
            error_log("Failed to add author. Author Data: " . print_r($authorData, true));
            echo json_encode(['status' => 'error', 'message' => 'Failed to add author. Please try again.']);
        }
    }
}

    
}

