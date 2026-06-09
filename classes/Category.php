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
        $query = "SELECT * FROM $this->table WHERE category_id = $category_id AND is_active = TRUE";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    // Get category by slug
    public function getCategoryBySlug($slug) {
        $slug = $this->conn->real_escape_string($slug);
        
        $query = "SELECT * FROM $this->table WHERE slug = '$slug' AND is_active = TRUE";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    // Get subcategories
    public function getSubcategories($parent_id) {
        $query = "SELECT * FROM $this->table WHERE parent_category_id = $parent_id AND is_active = TRUE ORDER BY category_name ASC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
