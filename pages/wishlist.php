<?php
require_once '../includes/config.php';
require_once '../classes/Wishlist.php';
require_once '../classes/Cart.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . 'pages/login.php');
}

$page_title = 'My Wishlist - TechStore';

$wishlist = new Wishlist($conn);
$cart = new Cart($conn);
$user_id = $_SESSION['user_id'];

// Remove item
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $wishlist->removeFromWishlist($user_id, $product_id);
    redirect(SITE_URL . 'pages/wishlist.php');
}

$wish_items = $wishlist->getUserWishlist($user_id);

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">My Wishlist</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">My Wishlist</h2>
    
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-lg-3">
            <div class="list-group">
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
                <a href="orders.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
                <a href="addresses.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                </a>
                <a href="wishlist.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-heart me-2"></i>Wishlist
                </a>
                <a href="<?php echo SITE_URL; ?>api/logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Wishlist Items -->
        <div class="col-lg-9">
            <?php if (empty($wish_items)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-heart fa-3x mb-3 text-muted"></i>
                <h4>Your wishlist is empty</h4>
                <p class="text-muted">Add items to your wishlist to save them for later</p>
                <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($wish_items as $item): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo getProductImageUrl($item['image'], $item['product_name']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        </div>
                        <div class="product-body">
                            <h6 class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                            <div class="product-rating">
                                <?php 
                                $rating = $item['rating'];
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<i class="fas fa-star ' . ($i <= $rating ? 'text-warning' : 'text-secondary') . '"></i>';
                                }
                                ?>
                                <small>(<?php echo $item['review_count']; ?>)</small>
                            </div>
                            <div class="product-price">
                                <?php echo formatCurrency($item['price']); ?>
                            </div>
                            <div class="mb-2">
                                <select class="form-select form-select-sm color-select" name="color">
                                    <option value="">Select Color</option>
                                    <option value="Gray">Gray</option>
                                    <option value="Black">Black</option>
                                    <option value="Dusty Gold">Dusty Gold</option>
                                </select>
                            </div>
                            <div class="product-buttons">
                                <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?php echo $item['product_id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Add
                                </button>
                                <a href="?remove=<?php echo $item['product_id']; ?>" class="btn btn-outline-danger btn-sm" 
                                   onclick="return confirm('Remove from wishlist?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="product-detail.php?id=<?php echo $item['product_id']; ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const SITE_URL = '<?php echo SITE_URL; ?>';
</script>

<?php include '../includes/footer.php'; ?>
