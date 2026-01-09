<?php


class CategoryModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }
  public function getAllCategories()
  {
    $sql = "SELECT qc.category_name, qc.id, COUNT(q.id) AS quote_count
        FROM quote_categories qc
        LEFT JOIN quotes q ON q.category_id = qc.id
        GROUP BY qc.id
        ORDER BY quote_count DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }
  
    public function getRandomFiveCategories()
    {
        $sql = "SELECT category_name, id FROM quote_categories ORDER BY RAND() LIMIT 5";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }



  // Fetch all categories used in quotes
  public function getCategories()
  {
    $query = "
        SELECT qc.category_name, qc.id, COUNT(q.id) AS quote_count
        FROM quote_categories qc
        LEFT JOIN quotes q ON q.category_id = qc.id
        GROUP BY qc.id
        ORDER BY quote_count DESC, qc.category_name ASC 
        LIMIT 5
        ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  // Fetch category name by category_name (for validation)
  public function getCategoryByName($category_name)
  {
    $query = "SELECT category_name FROM quote_categories WHERE category_name = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }


  // Fetch all quotes for a specific category by category_name
  public function getQuotesByCategory($category_name)
  {
    $query = "
         SELECT q.quote_text, q.author_name,q.id, q.created_at FROM quotes q INNER JOIN quote_categories qc ON q.category_id = qc.id WHERE qc.category_name = ? 
         ORDER BY q.created_at DESC;
        ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }
}