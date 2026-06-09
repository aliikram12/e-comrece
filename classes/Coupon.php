<?php
// Coupon class for coupon operations

class Coupon {
    private $conn;
    private $table = 'coupons';
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Validate coupon
    public function validateCoupon($coupon_code, $cart_total) {
        $coupon_code = $this->conn->real_escape_string($coupon_code);
        
        $query = "SELECT * FROM $this->table 
                  WHERE coupon_code = '$coupon_code' 
                  AND status = 'active' 
                  AND (valid_until IS NULL OR valid_until >= CURDATE()) 
                  AND (usage_limit IS NULL OR usage_count < usage_limit)";
        
        $result = $this->conn->query($query);
        
        if ($result->num_rows === 0) {
            return array('valid' => false, 'message' => 'Invalid or expired coupon');
        }
        
        $coupon = $result->fetch_assoc();
        
        // Check minimum purchase
        if ($coupon['min_purchase'] && $cart_total < $coupon['min_purchase']) {
            return array(
                'valid' => false, 
                'message' => 'Minimum purchase amount of ' . formatCurrency($coupon['min_purchase']) . ' required'
            );
        }
        
        // Calculate discount
        $discount = 0;
        if ($coupon['discount_type'] === 'percentage') {
            $discount = ($cart_total * $coupon['discount_value']) / 100;
        } else {
            $discount = $coupon['discount_value'];
        }
        
        // Apply max discount if set
        if ($coupon['max_discount'] && $discount > $coupon['max_discount']) {
            $discount = $coupon['max_discount'];
        }
        
        return array(
            'valid' => true,
            'coupon_id' => $coupon['coupon_id'],
            'discount' => $discount,
            'discount_type' => $coupon['discount_type'],
            'discount_value' => $coupon['discount_value']
        );
    }
    
    // Get coupon by code
    public function getCouponByCode($coupon_code) {
        $coupon_code = $this->conn->real_escape_string($coupon_code);
        
        $query = "SELECT * FROM $this->table WHERE coupon_code = '$coupon_code'";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
    
    // Increment usage count
    public function incrementUsage($coupon_id) {
        $query = "UPDATE $this->table SET usage_count = usage_count + 1 WHERE coupon_id = $coupon_id";
        
        return $this->conn->query($query);
    }
}
?>
