<?php

require_once __DIR__ . '/../models/BlogModel.php';

class BlogController
{
  private $conn;
  private $blogModel;


  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->blogModel = new BlogModel($conn);
  }

  public function index()
  {
    // Fetch all blogs
    $blogs = $this->blogModel->getAllBlogs();

    // Pass blogs to the view
    include __DIR__ . '/../views/pages/blogs.php';
  }

  public function view($id)
  {
    $blog = $this->blogModel->getBlogById($id);

    if ($blog) {
      require_once __DIR__ . '/../views/pages/view.php';
    } else {
      require_once __DIR__ . '/../views/pages/404-page.php';
    }
  }


}