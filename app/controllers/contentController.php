<?php

require_once __DIR__ . '/../models/QuoteModel.php'; 
require_once __DIR__ . '/../models/UserModel.php'; 
require_once __DIR__ . '/../models/LikeModel.php'; 
require_once __DIR__ . '/../models/SaveModel.php'; 
require_once __DIR__ . '/../../config/utilis.php';

class ContentController
{
  private $conn;
  private $quoteModel;
  private $likeModel;
  private $userModel;
  private $saveModel;
  
  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->userModel = new UserModel($conn);
    $this->quoteModel = new QuoteModel($conn); 
    $this->likeModel = new LikeModel($conn); 
    $this->saveModel = new SaveModel($conn); 
  }


public function customizeQuote($quoteId) {
    if (!isset($quoteId)) {
        echo "Quote ID is required.";
        return;
    }

    // Get quote details
    $quote = $this->quoteModel->getQuoteById($quoteId);

    // Check if quote exists
    if (!$quote) {
        echo "Quote not found.";
        require_once __DIR__ . '/../views/pages/404-page.php';
        return;
    }

    // Get the like count for the quote
    $likeCount = $this->likeModel->getLikeCount($quoteId);
    $saveCount = $this->saveModel->getSaveCount($quoteId);


    // Pass quote and like count to the view
    $quote['like_count'] = $likeCount;
    $quote['save_count'] = $saveCount;


    require_once __DIR__ . '/../views/content_create/customize-quote.php';
}



  
 
}
