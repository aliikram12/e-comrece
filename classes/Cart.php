<?php
// Cart class for cart operations

class Cart {
    private $conn;
    private $table = 'cart';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Add to cart
    public function addToCart($product_id, $quantity = 1, $user_id = null, $variant_id = null) {
        $session_id = session_id();
        
        // Check if product already in cart
        $check_query = "SELECT cart_id, quantity FROM $this->table 
                        WHERE product_id = $product_id ";
        
        if ($user_id) {
            $check_query .= "AND user_id = $user_id";
        } else {
            $check_query .= "AND session_id = '$session_id'";
        }
        
        if ($variant_id) {
            $check_query .= " AND variant_id = $variant_id";
        }
        
        $result = $this->conn->query($check_query);
        
        if ($result->num_rows > 0) {
            // Update quantity
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;
            
            $update_query = "UPDATE $this->table SET quantity = $new_quantity WHERE cart_id = " . $row['cart_id'];
            return $this->conn->query($update_query);
        } else {
            // Insert new item
            $insert_query = "INSERT INTO $this->table (product_id, quantity, user_id, session_id, variant_id) 
                             VALUES ($product_id, $quantity, ";
            
            if ($user_id) {
                $insert_query .= "$user_id, NULL, ";
            } else {
                $insert_query .= "NULL, '$session_id', ";
            }
            
            $insert_query .= ($variant_id ? $variant_id : "NULL") . ")";
            
            return $this->conn->query($insert_query);
        }
    }
    
    // Get cart items
    public function getCartItems($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT c.*, p.product_name, p.price, p.image, pv.color, pv.size 
                  FROM $this->table c 
                  INNER JOIN products p ON c.product_id = p.product_id 
                  LEFT JOIN product_variants pv ON c.variant_id = pv.variant_id 
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = $user_id";
        } else {
            $query .= "c.session_id = '$session_id'";
        }
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get cart count
    public function getCartCount($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT SUM(quantity) as total FROM $this->table WHERE ";
        
        if ($user_id) {
            $query .= "user_id = $user_id";
        } else {
            $query .= "session_id = '$session_id'";
        }
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Get cart total
    public function getCartTotal($user_id = null) {
        $session_id = session_id();
        
        $query = "SELECT SUM(p.price * c.quantity) as total FROM $this->table c 
                  INNER JOIN products p ON c.product_id = p.product_id 
                  WHERE ";
        
        if ($user_id) {
            $query .= "c.user_id = $user_id";
        } else {
            $query .= "c.session_id = '$session_id'";
        }
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Update cart item quantity
    public function updateQuantity($cart_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cart_id);
        }
        
        $query = "UPDATE $this->table SET quantity = $quantity WHERE cart_id = $cart_id";
        return $this->conn->query($query);
    }
    
    // Remove from cart
    public function removeFromCart($cart_id) {
        $query = "DELETE FROM $this->table WHERE cart_id = $cart_id";
        return $this->conn->query($query);
    }
    
    // Clear cart
    public function clearCart($user_id = null) {
        $session_id = session_id();
        
        $query = "DELETE FROM $this->table WHERE ";
        
        if ($user_id) {
            $query .= "user_id = $user_id";
        } else {
            $query .= "session_id = '$session_id'";
        }
        
        return $this->conn->query($query);
    }
    
    // Merge guest cart to user cart
    public function mergeCart($user_id) {
        $session_id = session_id();
        
        $query = "UPDATE $this->table SET user_id = $user_id, session_id = NULL 
                  WHERE session_id = '$session_id' AND user_id IS NULL";
        
        return $this->conn->query($query);
    }
}
?>
