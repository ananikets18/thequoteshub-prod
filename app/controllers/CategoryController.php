<?php

require_once __DIR__ . '/../models/CategoryModel.php';


class CategoryController
{
  private $categoryModel;

  public function __construct($conn)
  {

    $this->categoryModel = new CategoryModel($conn);
  }
  public function index()
  {
    $categories = $this->categoryModel->getAllCategories();

    require_once __DIR__ . '/../views/category/index.php';
  }


  // View method for displaying quotes in a specific category by name
  public function viewCategory($category_name)
  {
    // Fetch category (by name for validation)
    $category = $this->categoryModel->getCategoryByName($category_name);

    // Fetch quotes in this category
    $quotes = $this->categoryModel->getQuotesByCategory($category_name);

    // If category exists, show quotes, else show 404
    if ($category) {
      require_once __DIR__ . '/../views/category/view.php';
    } else {
      require_once __DIR__ . '/../views/pages/404-page.php';
    }
  }
}