<?php
require_once '../includes/config.php';
require_once '../classes/Cart.php';
require_once '../classes/Product.php';

$page_title = 'Shopping Cart - TechStore';

$cart = new Cart($conn);
$product = new Product($conn);

$user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
$cart_items = $cart->getCartItems($user_id);
$cart_total = $cart->getCartTotal($user_id);

// Update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $cart->updateQuantity($cart_id, $quantity);
    redirect(SITE_URL . 'pages/cart.php');
}

// Remove item
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $cart->removeFromCart($cart_id);
    redirect(SITE_URL . 'pages/cart.php');
}

// Clear cart
if (isset($_GET['clear'])) {
    $cart->clearCart($user_id);
    redirect(SITE_URL . 'pages/cart.php');
}

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Cart</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">Shopping Cart</h2>
    
    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['cart_error']; 
            unset($_SESSION['cart_error']);
            ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Start shopping to add items to your cart</p>
                <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
            <?php else: ?>
            
            <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="<?php echo getProductImageUrl($item['image'], $item['product_name']); ?>" 
                         alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                </div>
                
                <div class="cart-item-details">
                    <h6 class="cart-item-name"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                    <?php if (!empty($item['color']) || !empty($item['size'])): ?>
                    <small class="text-muted">
                        <?php echo !empty($item['color']) ? 'Color: ' . htmlspecialchars($item['color']) . ' | ' : ''; ?>
                        <?php echo !empty($item['size']) ? 'Size: ' . htmlspecialchars($item['size']) : ''; ?>
                    </small>
                    <?php endif; ?>
                    <p class="cart-item-price"><?php echo formatCurrency($item['price']); ?></p>
                </div>
                
                <div>
                    <form method="POST" class="quantity-selector">
                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                        <input type="hidden" name="update_quantity" value="1">
                        <button type="button" class="btn btn-outline-secondary qty-btn qty-decrease">-</button>
                        <input type="number" class="form-control qty-input" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <button type="button" class="btn btn-outline-secondary qty-btn qty-increase">+</button>
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
                    </form>
                </div>
                
                <div class="text-end">
                    <p class="fw-bold"><?php echo formatCurrency($item['price'] * $item['quantity']); ?></p>
                    <a href="?remove=<?php echo $item['cart_id']; ?>" class="btn btn-sm btn-danger" 
                       onclick="return confirm('Remove this item?')">
                        <i class="fas fa-trash"></i> Remove
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="mt-4">
                <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>

                <a href="<?php echo SITE_URL; ?>pages/checkout.php" class="btn btn-primary ms-2">
                    <i class="fas fa-lock me-2"></i>Proceed to Checkout
                </a>

                <a href="?clear" class="btn btn-outline-danger float-end" 
                   onclick="return confirm('Clear entire cart?')">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </a>
            </div>
            
            <?php endif; ?>
        </div>
        
        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="order-summary">
                <h5 class="mb-4">Order Summary</h5>
                
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span><?php echo formatCurrency($cart_total); ?></span>
                </div>
                
                <div class="summary-item">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                
                <div class="summary-item">
                    <span>Tax:</span>
                    <span><?php echo formatCurrency($cart_total * 0.1); ?></span>
                </div>
                
                <div class="summary-item">
                    <span>Total:</span>
                    <span><?php echo formatCurrency($cart_total * 1.1); ?></span>
                </div>
                
                <form method="POST" action="<?php echo SITE_URL; ?>pages/checkout.php" class="mt-4">
                    <?php if (!empty($cart_items)): ?>
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                        <i class="fas fa-lock me-2"></i>Proceed to Checkout
                    </button>
                    <?php endif; ?>
                    <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-outline-secondary w-100">
                        Continue Shopping
                    </a>
                </form>
                
                <!-- Promo Code -->
                <div class="mt-4">
                    <p class="small text-muted">Have a promo code?</p>
                    <div class="input-group">
                        <input type="text" class="form-control" id="promo_code" placeholder="Enter code">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
