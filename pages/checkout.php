<?php
require_once '../includes/config.php';
require_once '../classes/Cart.php';
require_once '../classes/Order.php';
require_once '../classes/Coupon.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . 'pages/login.php');
}

$page_title = 'Checkout - TechStore';

$cart = new Cart($conn);
$order = new Order($conn);
$coupon = new Coupon($conn);

$user_id = $_SESSION['user_id'];
$cart_items = $cart->getCartItems($user_id);
$cart_total = $cart->getCartTotal($user_id);

if (empty($cart_items)) {
    redirect(SITE_URL . 'pages/cart.php');
}

// Ensure all items have a color selected before allowing checkout
foreach ($cart_items as $item) {
    if (empty($item['color'])) {
        // We could use a session flash message here, but redirecting to cart is a good start
        // Set a session variable to show the error on the cart page
        $_SESSION['cart_error'] = 'One or more items in your cart do not have a color selected. Please remove them and add them again with a color selected.';
        redirect(SITE_URL . 'pages/cart.php');
    }
}

// Calculate totals
$subtotal = $cart_total;
$tax = $subtotal * 0.1; // 10% tax
$shipping = $subtotal > 50 ? 0 : 10; // Free shipping over $50
$discount = 0;
$total = $subtotal + $tax + $shipping - $discount;

// Process order
$order_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $order_message = '<div class="alert alert-danger">Security error: Invalid request. Please try again.</div>';
    } else {
        // Check if all items have a color selected
        $missing_color = false;
    foreach ($cart_items as $item) {
        if (empty($item['color'])) {
            $missing_color = true;
            break;
        }
    }
    
    if ($missing_color) {
        $order_message = '<div class="alert alert-danger">Error: One or more items in your cart do not have a color selected. Please go back to the cart, remove those items, and add them again with a color selected.</div>';
    } else {
        $shipping_address = sanitize($_POST['street_address'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['postal_code'] . ', ' . $_POST['country']);
        $billing_address = $_POST['same_as_shipping'] ? $shipping_address : sanitize($_POST['billing_street'] . ', ' . $_POST['billing_city'] . ', ' . $_POST['billing_state'] . ', ' . $_POST['billing_postal_code'] . ', ' . $_POST['billing_country']);
        $payment_method = sanitize($_POST['payment_method']);
    
    // Create order
    $order_result = $order->createOrder(
        $user_id,
        $total,
        $tax,
        $shipping,
        $discount,
        $payment_method,
        $shipping_address,
        $billing_address
    );
    
    if ($order_result['success']) {
        $order_id = $order_result['order_id'];
        
        // Add order items
        foreach ($cart_items as $item) {
            $order->addOrderItem(
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );
        }
        
        // Clear cart
        $cart->clearCart($user_id);
        
        // Redirect to order confirmation
        redirect(SITE_URL . 'pages/order-confirmation.php?order_id=' . $order_id);
    } else {
        $order_message = '<div class="alert alert-danger">Error creating order. Please try again.</div>';
    }
    }
    }
}

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">Checkout</h2>
    
    <?php echo $order_message; ?>
    
    <form method="POST" class="needs-validation">
        <div class="row">
            <div class="col-lg-8">
                <!-- Shipping Address -->
                <div class="checkout-section">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Street Address *</label>
                        <input type="text" class="form-control" name="street_address" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State/Province *</label>
                            <input type="text" class="form-control" name="state" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country *</label>
                            <input type="text" class="form-control" name="country" required>
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="same_shipping" name="same_as_shipping" checked>
                        <label class="form-check-label" for="same_shipping">
                            Billing address same as shipping
                        </label>
                    </div>
                </div>
                
                <!-- Billing Address -->
                <div class="checkout-section" id="billing_section" style="display: none;">
                    <h5><i class="fas fa-credit-card me-2"></i>Billing Address</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="billing_first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="billing_last_name">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Street Address</label>
                        <input type="text" class="form-control" name="billing_street">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="billing_city">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State/Province</label>
                            <input type="text" class="form-control" name="billing_state">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control" name="billing_postal_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="billing_country">
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="checkout-section">
                    <h5><i class="fas fa-wallet me-2"></i>Payment Method</h5>
                    
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" id="payment_card" name="payment_method" value="credit_card" checked>
                        <label class="form-check-label" for="payment_card">
                            Credit/Debit Card
                        </label>
                    </div>
                    
                    <div class="payment-details" id="card_details_section">
                        <div class="mb-3">
                            <label class="form-label">Card Number *</label>
                            <input type="text" class="form-control card-input" placeholder="1234 5678 9012 3456" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date *</label>
                                <input type="text" class="form-control card-input" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-control card-input" placeholder="123" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" id="payment_cod" name="payment_method" value="cash_on_delivery">
                        <label class="form-check-label" for="payment_cod">
                            Cash on Delivery
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="payment_bank" name="payment_method" value="bank_transfer">
                        <label class="form-check-label" for="payment_bank">
                            Bank Transfer
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h5 class="mb-4">Order Summary</h5>
                    
                    <!-- Order Items -->
                    <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="summary-item">
                            <span><?php echo htmlspecialchars($item['product_name']); ?> x<?php echo $item['quantity']; ?></span>
                            <span><?php echo formatCurrency($item['price'] * $item['quantity']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span><?php echo formatCurrency($subtotal); ?></span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Tax (10%):</span>
                        <span><?php echo formatCurrency($tax); ?></span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Shipping:</span>
                        <span><?php echo $shipping == 0 ? 'Free' : formatCurrency($shipping); ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                    <div class="summary-item">
                        <span>Discount:</span>
                        <span>-<?php echo formatCurrency($discount); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-item">
                        <span>Total:</span>
                        <span><?php echo formatCurrency($total); ?></span>
                    </div>
                    
                    <input type="hidden" name="place_order" value="1">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                        <i class="fas fa-check me-2"></i>Place Order
                    </button>
                    
                    <a href="<?php echo SITE_URL; ?>pages/cart.php" class="btn btn-outline-secondary w-100 mt-2">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('same_shipping').addEventListener('change', function() {
    document.getElementById('billing_section').style.display = this.checked ? 'none' : 'block';
});

document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var cardSection = document.getElementById('card_details_section');
        var cardInputs = document.querySelectorAll('.card-input');
        
        if (this.value === 'credit_card') {
            cardSection.style.display = 'block';
            cardInputs.forEach(function(input) { input.required = true; });
        } else {
            cardSection.style.display = 'none';
            cardInputs.forEach(function(input) { input.required = false; });
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
