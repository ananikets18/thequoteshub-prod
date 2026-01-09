<?php

class BlogModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function createBlog($adminId, $title, $content)
  {
    $stmt = $this->conn->prepare("INSERT INTO blogs (admin_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $adminId, $title, $content);
    $stmt->execute();
  }
  public function getBlogsByAdminId($adminId)
  {
    $query = "SELECT id, title FROM blogs WHERE admin_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    $blogs = [];
    while ($row = $result->fetch_assoc()) {
      $blogs[] = $row;
    }

    return $blogs;
  }

  public function getBlogById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function updateBlog($id, $title, $content)
  {
    $stmt = $this->conn->prepare("UPDATE blogs SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
  }

  public function deleteBlog($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }

  public function getAllBlogs()
  {
    $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
    $result = $this->conn->query($sql);

    $result = mysqli_query($this->conn, $sql);

    if (!$result) {
      die("Query failed: " . mysqli_error($this->conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
}