<?php
// Order class for order operations

class Order {
    private $conn;
    private $table = 'orders';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Create order
    public function createOrder($user_id, $total_amount, $tax, $shipping_cost, $discount_amount, 
                               $payment_method, $shipping_address, $billing_address = null) {
        // Generate order number
        $order_number = 'ORD' . date('YmdHis') . rand(1000, 9999);
        
        $query = "INSERT INTO $this->table 
                  (order_number, user_id, total_amount, tax, shipping_cost, discount_amount, 
                   payment_method, order_status, payment_status, shipping_address, billing_address) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siddddsss", 
            $order_number, $user_id, $total_amount, $tax, $shipping_cost, 
            $discount_amount, $payment_method, $shipping_address, $billing_address
        );
        
        if ($stmt->execute()) {
            return array('success' => true, 'order_id' => $stmt->insert_id, 'order_number' => $order_number);
        }
        
        return array('success' => false, 'message' => 'Order creation failed');
    }
    
    // Add order items
    public function addOrderItem($order_id, $product_id, $quantity, $unit_price) {
        $total_price = $quantity * $unit_price;
        
        $query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidd", $order_id, $product_id, $quantity, $unit_price, $total_price);
        
        return $stmt->execute();
    }
    
    // Get order by ID
    public function getOrderById($order_id) {
        $query = "SELECT * FROM $this->table WHERE order_id = $order_id";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    // Get order by order number
    public function getOrderByNumber($order_number) {
        $order_number = $this->conn->real_escape_string($order_number);
        
        $query = "SELECT * FROM $this->table WHERE order_number = '$order_number'";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    // Get user orders
    public function getUserOrders($user_id, $limit = 50) {
        $query = "SELECT * FROM $this->table 
                  WHERE user_id = $user_id 
                  ORDER BY created_at DESC 
                  LIMIT $limit";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get order items
    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.product_name, p.image 
                  FROM order_items oi 
                  INNER JOIN products p ON oi.product_id = p.product_id 
                  WHERE oi.order_id = $order_id";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Update order status
    public function updateOrderStatus($order_id, $status) {
        $query = "UPDATE $this->table SET order_status = ? WHERE order_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $order_id);
        
        return $stmt->execute();
    }
    
    // Update payment status
    public function updatePaymentStatus($order_id, $status) {
        $query = "UPDATE $this->table SET payment_status = ? WHERE order_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $order_id);
        
        return $stmt->execute();
    }
    
    // Cancel order
    public function cancelOrder($order_id) {
        $query = "UPDATE $this->table SET order_status = 'cancelled' WHERE order_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        
        return $stmt->execute();
    }
    
    // Get all orders (admin)
    public function getAllOrders($page = 1, $limit = 50) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT o.*, u.email, u.first_name, u.last_name 
                  FROM $this->table o 
                  INNER JOIN users u ON o.user_id = u.user_id 
                  ORDER BY o.created_at DESC 
                  LIMIT $limit OFFSET $offset";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get total orders
    public function getTotalOrders() {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // Get sales revenue
    public function getSalesRevenue() {
        $query = "SELECT SUM(total_amount) as revenue FROM $this->table WHERE payment_status = 'completed'";
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['revenue'] ? $row['revenue'] : 0;
    }
}
?>
