<?php
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../models/AuthorModel.php';
require_once __DIR__ . '/../../config/utilis.php';

class SearchController 
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
    
    public function search($query)
{
    if (strlen($query) < 3) {
        return [
            'status' => 'error',
            'message' => 'Please enter at least 3 characters to search.',
            'results' => []
        ];
    }

    $results = $this->quoteModel->searchQuotes($query);

    if (empty($results)) {
        return [
            'status' => 'error',
            'message' => 'No results found.',
            'results' => []
        ];
    }

    $output = [];
    foreach ($results as $result) {
        $cleanedContent = decodeCleanAndRemoveTags(decodeAndCleanText($result['content'])); // Clean the text
        $highlightedContent = $this->highlightText($cleanedContent, $query);

        if ($result['type'] === 'quote') {
            $output[] = [
                'id' => $result['id'],
                'type' => 'quote',
                'content' => $highlightedContent,
                'author' => $result['author_name']
            ];
        } elseif ($result['type'] === 'author') {
            $output[] = [
                'id' => $result['id'],
                'type' => 'author',
                'content' => $highlightedContent,
                'author' => null
            ];
        } elseif ($result['type'] === 'user') {
            $output[] = [
                'id' => $result['id'],
                'type' => 'user',
                'content' => $highlightedContent,
                'name' => $result['user_name'],  
                'username' => $result['content'],  
            ];
        }
    }

    return [
        'status' => 'success',
        'message' => 'Results found.',
        'results' => $output
    ];
}




    private function highlightText($text, $query) {
        return str_ireplace($query, "<mark>$query</mark>", htmlspecialchars($text));
    }
    
}

