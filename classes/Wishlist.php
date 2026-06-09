<?php
// Wishlist class for wishlist operations

class Wishlist {
    private $conn;
    private $table = 'wishlist';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Add to wishlist
    public function addToWishlist($user_id, $product_id) {
        // Check if already in wishlist
        $check_query = "SELECT wishlist_id FROM $this->table WHERE user_id = $user_id AND product_id = $product_id";
        
        $result = $this->conn->query($check_query);
        
        if ($result->num_rows > 0) {
            return array('success' => false, 'message' => 'Product already in wishlist');
        }
        
        $query = "INSERT INTO $this->table (user_id, product_id) VALUES (?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Added to wishlist');
        }
        
        return array('success' => false, 'message' => 'Failed to add to wishlist');
    }
    
    // Remove from wishlist
    public function removeFromWishlist($user_id, $product_id) {
        $query = "DELETE FROM $this->table WHERE user_id = $user_id AND product_id = $product_id";
        
        return $this->conn->query($query);
    }
    
    // Get user wishlist
    public function getUserWishlist($user_id) {
        $query = "SELECT p.* FROM products p 
                  INNER JOIN $this->table w ON p.product_id = w.product_id 
                  WHERE w.user_id = $user_id 
                  ORDER BY w.created_at DESC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Check if product in wishlist
    public function isInWishlist($user_id, $product_id) {
        $query = "SELECT wishlist_id FROM $this->table WHERE user_id = $user_id AND product_id = $product_id";
        
        $result = $this->conn->query($query);
        
        return $result->num_rows > 0;
    }
    
    // Get wishlist count
    public function getWishlistCount($user_id) {
        $query = "SELECT COUNT(*) as count FROM $this->table WHERE user_id = $user_id";
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
}
?>
