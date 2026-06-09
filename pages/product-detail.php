<?php
require_once '../includes/config.php';
require_once '../classes/Product.php';
require_once '../classes/Review.php';
require_once '../classes/Cart.php';
require_once '../classes/Wishlist.php';

$product_id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$product_id) {
    redirect(SITE_URL . 'pages/shop.php');
}

$product = new Product($conn);
$review = new Review($conn);
$cart = new Cart($conn);
$wishlist_obj = new Wishlist($conn);

$prod = $product->getProductById($product_id);

if (!$prod) {
    redirect(SITE_URL . 'pages/shop.php');
}

$page_title = $prod['product_name'] . ' - TechStore';

// Add to recently viewed
$product->addRecentlyViewed($product_id, isLoggedIn() ? $_SESSION['user_id'] : null);

// Get product images
$images = $product->getProductImages($product_id);
$variants = $product->getProductVariants($product_id);
$reviews = $review->getProductReviews($product_id);

// Check if in wishlist
$in_wishlist = false;
if (isLoggedIn()) {
    $in_wishlist = $wishlist_obj->isInWishlist($_SESSION['user_id'], $product_id);
}

// Add review
$review_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    if (!isLoggedIn()) {
        $review_message = '<div class="alert alert-warning">Please login to leave a review</div>';
    } else {
        $rating = intval($_POST['rating']);
        $title = sanitize($_POST['review_title']);
        $review_text = sanitize($_POST['review_text']);
        
        if ($rating >= 1 && $rating <= 5 && !empty($title)) {
            $result = $review->addReview($product_id, $_SESSION['user_id'], $rating, $title, $review_text);
            $review_message = '<div class="alert alert-success">' . $result['message'] . '</div>';
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
            <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($prod['product_name']); ?></li>
        </ol>
    </nav>
    
    <!-- Product Detail -->
    <div class="row product-detail">
        <!-- Images -->
        <div class="col-lg-5">
            <div class="product-images">
                <img src="<?php echo getProductImageUrl($prod['image'], $prod['product_name']); ?>" 
                     class="main-image" alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                
                <?php if (!empty($images)): ?> 
                <div class="thumbnail-images">
                    <div class="thumbnail active" onclick="changeImage(this)">
                        <img src="<?php echo getProductImageUrl($prod['image'], $prod['product_name']); ?>" 
                             alt="Main">
                    </div>
                    <?php foreach ($images as $img): ?>
                    <div class="thumbnail" onclick="changeImage(this)">
                        <img src="<?php echo getProductImageUrl($img['image_url'], $prod['product_name']); ?>" 
                             alt="<?php echo htmlspecialchars($img['alt_text']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-7">
            <div class="product-info">
                <h1><?php echo htmlspecialchars($prod['product_name']); ?></h1>
                
                <!-- Rating -->
                <div class="product-rating-section">
                    <div class="product-rating mb-2">
                        <?php 
                        $rating = $prod['rating'];
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<i class="fas fa-star ' . ($i <= $rating ? 'text-warning' : 'text-secondary') . '"></i>';
                        }
                        ?>
                        <span class="ms-2"><?php echo $rating; ?> out of 5</span>
                    </div>
                    <p class="text-muted"><?php echo $prod['review_count']; ?> customer reviews</p>
                </div>
                
                <!-- Price -->
                <div class="product-price-section">
                    <div class="price-display">
                        <?php echo formatCurrency($prod['price']); ?>
                        <?php if (!empty($prod['original_price'])): ?>
                        <span class="product-original-price"><?php echo formatCurrency($prod['original_price']); ?></span>
                        <span class="badge bg-danger ms-2">
                            -<?php echo round((($prod['original_price'] - $prod['price']) / $prod['original_price']) * 100); ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-success mt-2">
                        <i class="fas fa-check-circle"></i> In Stock (<?php echo $prod['stock_quantity']; ?> available)
                    </p>
                </div>
                
                <!-- Description -->
                <div class="product-description">
                    <?php echo $prod['description']; ?>
                </div>
                
                <!-- Variants -->
                <?php if (!empty($variants)): ?>
                <div class="mb-4">
                    <h6>Choose Options:</h6>
                    <div class="row">
                        <?php foreach ($variants as $variant): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($variant['variant_name']); ?></label>
                            <select class="form-select variant-select">
                                <option value="">Select <?php echo htmlspecialchars($variant['variant_name']); ?></option>
                                <?php if ($variant['color']): ?>
                                <option value="<?php echo $variant['variant_id']; ?>"><?php echo htmlspecialchars($variant['color']); ?></option>
                                <?php endif; ?>
                                <?php if ($variant['size']): ?>
                                <option value="<?php echo $variant['variant_id']; ?>"><?php echo htmlspecialchars($variant['size']); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Custom Color Selection -->
                <div class="mb-4">
                    <h6>Choose Color:</h6>
                    <select class="form-select color-select" name="color">
                        <option value="">Select Color</option>
                        <option value="Gray">Gray</option>
                        <option value="Black">Black</option>
                        <option value="Dusty Gold">Dusty Gold</option>
                    </select>
                </div>
                
                <!-- Quantity & Actions -->
                <div class="mb-4">
                    <form method="POST" action="<?php echo SITE_URL; ?>api/add-to-cart.php" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Quantity:</label>
                            <div class="quantity-selector">
                                <button type="button" class="btn btn-outline-secondary qty-btn qty-decrease">-</button>
                                <input type="number" class="form-control qty-input" value="1" min="1" name="quantity">
                                <button type="button" class="btn btn-outline-secondary qty-btn qty-increase">+</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="button" class="btn btn-primary btn-lg flex-grow-1 add-to-cart-btn" data-product-id="<?php echo $product_id; ?>">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="toggleWishlist(<?php echo $product_id; ?>)">
                                    <i class="fas fa-heart <?php echo $in_wishlist ? 'text-danger' : ''; ?>"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Additional Info -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-0"><strong>SKU:</strong></p>
                                <p class="text-muted"><?php echo htmlspecialchars($prod['sku'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><strong>Category:</strong></p>
                                <p class="text-muted"><?php echo htmlspecialchars($prod['category_name']); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><strong>Availability:</strong></p>
                                <p class="text-muted">
                                    <?php echo $prod['stock_quantity'] > 0 ? '<span class="text-success">In Stock</span>' : '<span class="text-danger">Out of Stock</span>'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-lg-8">
            <h3 class="mb-4">Customer Reviews</h3>
            
            <?php echo $review_message; ?>
            
            <!-- Add Review Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Leave a Review</h5>
                </div>
                <div class="card-body">
                    <?php if (!isLoggedIn()): ?>
                    <p class="text-muted">Please <a href="login.php">login</a> to leave a review.</p>
                    <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="add_review" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">Rating:</label>
                            <div class="rating-input">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label class="me-3">
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" required>
                                    <span class="ms-1">
                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                        <i class="fas fa-star <?php echo $j <= $i ? 'text-warning' : 'text-secondary'; ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="review_title" class="form-label">Review Title:</label>
                            <input type="text" class="form-control" id="review_title" name="review_title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="review_text" class="form-label">Your Review:</label>
                            <textarea class="form-control" id="review_text" name="review_text" rows="5"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Reviews List -->
            <h5 class="mb-3"><?php echo count($reviews); ?> Reviews</h5>
            
            <?php if (empty($reviews)): ?>
            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
            <?php else: ?>
                <?php foreach ($reviews as $rev): ?>
                <div class="review">
                    <div class="review-header">
                        <div>
                            <p class="reviewer-info mb-1">
                                <?php echo htmlspecialchars($rev['first_name'] . ' ' . $rev['last_name']); ?>
                            </p>
                            <p class="review-date">
                                <?php echo date('M d, Y', strtotime($rev['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= $rev['rating'] ? 'text-warning' : 'text-secondary'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <h6 class="review-title"><?php echo htmlspecialchars($rev['title']); ?></h6>
                    <p class="review-text"><?php echo htmlspecialchars($rev['review_text']); ?></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Related Products -->
        <div class="col-lg-4">
            <h5 class="mb-3">Related Products</h5>
            <div class="related-products">
                <?php
                $related = $product->getProductsByCategory($prod['category_id'], 1, 3);
                foreach ($related as $rel_prod):
                    if ($rel_prod['product_id'] != $product_id):
                ?>
                <div class="card mb-3">
                    <img src="<?php echo getProductImageUrl($rel_prod['image'], $rel_prod['product_name']); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($rel_prod['product_name']); ?>">
                    <div class="card-body">
                        <h6 class="card-title text-truncate"><?php echo htmlspecialchars($rel_prod['product_name']); ?></h6>
                        <p class="card-text text-primary fw-bold"><?php echo formatCurrency($rel_prod['price']); ?></p>
                        <a href="product-detail.php?id=<?php echo $rel_prod['product_id']; ?>" class="btn btn-sm btn-outline-primary w-100">
                            View Details
                        </a>
                    </div>
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function changeImage(element) {
    const img = element.querySelector('img');
    document.querySelector('.main-image').src = img.src;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    element.classList.add('active');
}

function toggleWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch('<?php echo SITE_URL; ?>api/wishlist.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

const SITE_URL = '<?php echo SITE_URL; ?>';
</script>

<?php include '../includes/footer.php'; ?>
