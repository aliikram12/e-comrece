<?php
// Category class for category operations

class Category {
    private $conn;
    private $table = 'categories';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Get all categories
    public function getAllCategories() {
        $query = "SELECT * FROM $this->table WHERE is_active = TRUE ORDER BY category_name ASC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get category by ID
    public function getCategoryById($category_id) {
        $query = "SELECT * FROM $this->table WHERE category_id = ? AND is_active = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get category by slug
    public function getCategoryBySlug($slug) {
        $query = "SELECT * FROM $this->table WHERE slug = ? AND is_active = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get subcategories
    public function getSubcategories($parent_id) {
        $query = "SELECT * FROM $this->table WHERE parent_category_id = ? AND is_active = TRUE ORDER BY category_name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
