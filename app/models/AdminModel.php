<?php

class AdminModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }
  // Get admin by email
  public function getAdminByEmail($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
  
  // Check if email already exists
  public function checkEmailExists($email)
  {
    $stmt = $this->conn->prepare("SELECT id FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
  }
  
  // Create new admin
  public function createAdmin($username, $email, $password)
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("INSERT INTO admin (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
      return $this->conn->insert_id;
    }
    return false;
  }
}