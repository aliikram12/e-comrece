<?php
// Product class for product operations

class Product {
    private $conn;
    private $table = 'products';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Get all products
    public function getAllProducts($page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.category_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  WHERE p.status = 'active' 
                  ORDER BY p.created_at DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get products by category
    public function getProductsByCategory($category_id, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.category_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  WHERE p.category_id = ? AND p.status = 'active' 
                  ORDER BY p.created_at DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $category_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get products for admin management
    public function getAdminProducts($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT p.*, c.category_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  ORDER BY p.created_at DESC 
                  LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Create a product
    public function createProduct($product_name, $description, $price, $original_price, $category_id, $stock_quantity, $sku, $status, $image, $vendor_id = null) {
        $slug = $this->generateSlug($product_name);
        $query = "INSERT INTO $this->table (product_name, slug, description, price, original_price, category_id, vendor_id, stock_quantity, sku, image, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssddiiisss", $product_name, $slug, $description, $price, $original_price, $category_id, $vendor_id, $stock_quantity, $sku, $image, $status);
        return $stmt->execute();
    }
    
    // Update a product
    public function updateProduct($product_id, $product_name, $description, $price, $original_price, $category_id, $stock_quantity, $sku, $status, $image) {
        $slug = $this->generateSlug($product_name, $product_id);
        $query = "UPDATE $this->table SET product_name = ?, slug = ?, description = ?, price = ?, original_price = ?, category_id = ?, stock_quantity = ?, sku = ?, image = ?, status = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssddiisssi", $product_name, $slug, $description, $price, $original_price, $category_id, $stock_quantity, $sku, $image, $status, $product_id);
        return $stmt->execute();
    }
    
    // Delete a product
    public function deleteProduct($product_id) {
        $query = "DELETE FROM $this->table WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }
    
    // Generate slug from text
    private function generateSlug($text, $currentProductId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
        $original = $slug;
        $count = 1;

        while (true) {
            $existing = $this->getProductBySlug($slug);
            if (!$existing || ($currentProductId && $existing['product_id'] == $currentProductId)) {
                break;
            }
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
    
    // Search products
    public function searchProducts($search_term, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $search_like = "%$search_term%";
        
        $query = "SELECT p.*, c.category_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  WHERE (p.product_name LIKE ? OR p.description LIKE ?) 
                  AND p.status = 'active' 
                  ORDER BY p.product_name ASC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $search_like, $search_like, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get product by ID
    public function getProductById($product_id) {
        $query = "SELECT p.*, c.category_name, u.first_name as vendor_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  LEFT JOIN users u ON p.vendor_id = u.user_id 
                  WHERE p.product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get product by slug
    public function getProductBySlug($slug) {
        $query = "SELECT p.*, c.category_name, u.first_name as vendor_name 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  LEFT JOIN users u ON p.vendor_id = u.user_id 
                  WHERE p.slug = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get featured products
    public function getFeaturedProducts($limit = 6) {
        $query = "SELECT * FROM $this->table 
                  WHERE status = 'active' AND original_price > 0 
                  ORDER BY rating DESC 
                  LIMIT $limit";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get best sellers
    public function getBestSellers($limit = 6) {
        $query = "SELECT p.* FROM $this->table p 
                  INNER JOIN order_items oi ON p.product_id = oi.product_id 
                  WHERE p.status = 'active' 
                  GROUP BY p.product_id 
                  ORDER BY COUNT(oi.order_item_id) DESC 
                  LIMIT $limit";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get new arrivals
    public function getNewArrivals($limit = 6) {
        $query = "SELECT * FROM $this->table 
                  WHERE status = 'active' 
                  ORDER BY created_at DESC 
                  LIMIT $limit";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get product images
    public function getProductImages($product_id) {
        $query = "SELECT * FROM product_images 
                  WHERE product_id = ? 
                  ORDER BY display_order ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get product variants
    public function getProductVariants($product_id) {
        $query = "SELECT * FROM product_variants 
                  WHERE product_id = ? 
                  ORDER BY created_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get total products count
    public function getTotalCount($category_id = null, $activeOnly = true) {
        $query = "SELECT COUNT(*) as total FROM $this->table";

        $conditions = [];
        $params = [];
        $types = "";
        
        if ($activeOnly) {
            $conditions[] = "status = 'active'";
        }
        if ($category_id) {
            $conditions[] = "category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        
        return $row['total'];
    }
    
    // Add to recently viewed
    public function addRecentlyViewed($product_id, $user_id = null) {
        $session_id = session_id();
        
        $query = "INSERT INTO recently_viewed (product_id, user_id, session_id) VALUES ($product_id, ";
        
        if ($user_id) {
            $query .= "$user_id, NULL)";
        } else {
            $query .= "NULL, '$session_id')";
        }
        
        return $this->conn->query($query);
    }
    
    // Get recently viewed products
    public function getRecentlyViewed($user_id = null, $limit = 4) {
        $session_id = session_id();
        
        $query = "SELECT p.* FROM $this->table p 
                  INNER JOIN recently_viewed rv ON p.product_id = rv.product_id 
                  WHERE ";
        
        if ($user_id) {
            $query .= "rv.user_id = $user_id ";
        } else {
            $query .= "rv.session_id = '$session_id' ";
        }
        
        $query .= "ORDER BY rv.viewed_at DESC LIMIT $limit";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
