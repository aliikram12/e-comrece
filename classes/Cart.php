<?php
// Cart class for cart operations

class Cart {
    private $conn;
    private $table = 'cart';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Add to cart
    public function addToCart($product_id, $quantity = 1, $user_id = null, $variant_id = null, $color = null) {
        $session_id = session_id();
        
        // Check if product already in cart
        $check_query = "SELECT cart_id, quantity FROM $this->table WHERE product_id = ?";
        $params = [$product_id];
        $types = "i";
        
        if ($user_id) {
            $check_query .= " AND user_id = ?";
            $params[] = $user_id;
            $types .= "i";
        } else {
            $check_query .= " AND session_id = ?";
            $params[] = $session_id;
            $types .= "s";
        }
        
        if ($variant_id) {
            $check_query .= " AND variant_id = ?";
            $params[] = $variant_id;
            $types .= "i";
        }
        
        if ($color) {
            $check_query .= " AND color = ?";
            $params[] = $color;
            $types .= "s";
        } else {
            $check_query .= " AND color IS NULL";
        }
        
        $stmt = $this->conn->prepare($check_query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;
            
            $update_query = "UPDATE $this->table SET quantity = ? WHERE cart_id = ?";
            $upd_stmt = $this->conn->prepare($update_query);
            $upd_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
            return $upd_stmt->execute();
        } else {
            // Insert new item
            $insert_query = "INSERT INTO $this->table (product_id, quantity, user_id, session_id, variant_id, color) VALUES (?, ?, ?, ?, ?, ?)";
            $ins_stmt = $this->conn->prepare($insert_query);
            $ins_stmt->bind_param("iiisis", $product_id, $quantity, $user_id, $session_id, $variant_id, $color);
            return $ins_stmt->execute();
        }
    }
    
    // Get cart items
    public function getCartItems($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT c.*, p.product_name, p.price, p.image, COALESCE(c.color, pv.color) as color, pv.size 
                  FROM $this->table c 
                  INNER JOIN products p ON c.product_id = p.product_id 
                  LEFT JOIN product_variants pv ON c.variant_id = pv.variant_id 
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);
        } else {
            $query .= "c.session_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $session_id);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get cart count
    public function getCartCount($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT SUM(quantity) as total FROM $this->table WHERE ";
        
        if ($user_id) {
            $query .= "user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);
        } else {
            $query .= "session_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $session_id);
        }
        
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Get cart total
    public function getCartTotal($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT SUM(p.price * c.quantity) as total FROM $this->table c 
                  INNER JOIN products p ON c.product_id = p.product_id 
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);
        } else {
            $query .= "c.session_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $session_id);
        }
        
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Update cart item quantity
    public function updateQuantity($cart_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cart_id);
        }
        
        $query = "UPDATE $this->table SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $cart_id);
        return $stmt->execute();
    }
    
    // Remove from cart
    public function removeFromCart($cart_id) {
        $query = "DELETE FROM $this->table WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        return $stmt->execute();
    }
    
    // Clear cart
    public function clearCart($user_id = null) {
        $session_id = session_id();
        
        $query = "DELETE FROM $this->table WHERE ";
        
        if ($user_id) {
            $query .= "user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);
        } else {
            $query .= "session_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $session_id);
        }
        
        return $stmt->execute();
    }
    
    // Merge guest cart to user cart
    public function mergeCart($user_id) {
        $session_id = session_id();
        
        $query = "UPDATE $this->table SET user_id = ?, session_id = NULL 
                  WHERE session_id = ? AND user_id IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $user_id, $session_id);
        return $stmt->execute();
    }
}
?>
