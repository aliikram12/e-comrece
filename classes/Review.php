<?php
// Review class for review operations

class Review {
    private $conn;
    private $table = 'reviews';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Add review
    public function addReview($product_id, $user_id, $rating, $title, $review_text, $order_id = null) {
        $query = "INSERT INTO $this->table 
                  (product_id, user_id, rating, title, review_text, order_id, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiiissi", $product_id, $user_id, $rating, $title, $review_text, $order_id);
        
        if ($stmt->execute()) {
            // Update product rating
            $this->updateProductRating($product_id);
            return array('success' => true, 'message' => 'Review submitted successfully');
        }
        
        return array('success' => false, 'message' => 'Review submission failed');
    }
    
    // Get product reviews
    public function getProductReviews($product_id, $status = 'approved') {
        $query = "SELECT r.*, u.first_name, u.last_name, u.profile_image 
                  FROM $this->table r 
                  INNER JOIN users u ON r.user_id = u.user_id 
                  WHERE r.product_id = $product_id AND r.status = '$status' 
                  ORDER BY r.created_at DESC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get review count for product
    public function getReviewCount($product_id) {
        $query = "SELECT COUNT(*) as count FROM $this->table WHERE product_id = $product_id AND status = 'approved'";
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    // Update product rating
    private function updateProductRating($product_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as count 
                  FROM $this->table 
                  WHERE product_id = $product_id AND status = 'approved'";
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        $rating = round($row['avg_rating'], 2);
        $count = $row['count'];
        
        $update_query = "UPDATE products SET rating = $rating, review_count = $count WHERE product_id = $product_id";
        
        return $this->conn->query($update_query);
    }
    
    // Approve review (admin)
    public function approveReview($review_id) {
        $query = "UPDATE $this->table SET status = 'approved' WHERE review_id = $review_id";
        
        if ($this->conn->query($query)) {
            // Get product_id and update rating
            $get_query = "SELECT product_id FROM $this->table WHERE review_id = $review_id";
            $result = $this->conn->query($get_query);
            $row = $result->fetch_assoc();
            
            $this->updateProductRating($row['product_id']);
            
            return true;
        }
        
        return false;
    }
}
?>
