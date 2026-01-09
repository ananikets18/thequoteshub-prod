<?php

class AuthorModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Get authors who need updates (update_author_info = false)
    public function getUnupdatedAuthors()
    {
        $stmt = $this->conn->prepare("
            SELECT DISTINCT author_name
            FROM quotes
            WHERE update_author_info = FALSE
        ");
        $stmt->execute();
        $result = $stmt->get_result();

        $authors = [];
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }

        return $authors;
    }
    public function createAuthor($authorData)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO authors (author_name, description, profession, author_img) 
            VALUES (?, ?, ?, ?)
        ");
      $stmt->bind_param(
        "ssss", 
        $authorData['author_name'], 
        $authorData['description'], 
        $authorData['profession'], 
        $authorData['author_img']
    );

    // Execute the query and log success or failure
    $success = $stmt->execute();
    error_log("Author Creation - Author Name: " . $authorData['author_name'] . ", Success: " . ($success ? 'Yes' : 'No'));

    return $success;
    }
    
    // Get details of a specific author by name
public function getAuthorDetailsByName($authorName)
{
    // Prepare the query to search by author_name
    $stmt = $this->conn->prepare("
        SELECT id, author_name, description, profession, author_img
        FROM authors
        WHERE author_name = ?
    ");
    
    // Bind the parameter for author name
    $stmt->bind_param("s", $authorName);
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize author details
    $authorDetails = null;
    
    // Fetch the author details if available
    if ($row = $result->fetch_assoc()) {
        $authorDetails = $row;
    }

    // Return the author details (or null if not found)
    return $authorDetails;
}



}
