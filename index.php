<?php
require_once 'includes/config.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

$page_title = 'Home - TechStore';

// Instantiate classes
$product = new Product($conn);
$category = new Category($conn);

// Get featured products
$featured = $product->getFeaturedProducts(6);
$bestsellers = $product->getBestSellers(6);
$new_arrivals = $product->getNewArrivals(6);

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-start position-relative z-index-2">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill animate-slide-up" style="border: 1px solid rgba(124, 58, 237, 0.3);">New Arrivals Available</span>
                <h1 class="animate-slide-up delay-100 display-3 fw-bold mb-4" style="line-height: 1.1;">
                    Elevate Your <br>
                    <span style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Tech Experience</span>
                </h1>
                <p class="animate-slide-up delay-200 lead text-secondary mb-5" style="max-width: 90%;">
                    Discover the latest in premium technology products carefully curated for professionals and enthusiasts. Uncompromising quality meets sleek design.
                </p>
                <div class="hero-buttons animate-slide-up delay-300 d-flex gap-3">
                    <a href="pages/shop.php" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg d-flex align-items-center gap-2">
                        Shop Collection <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="pages/about.php" class="btn btn-outline-light btn-lg px-5 rounded-pill d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.1);">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 position-relative d-none d-lg-block">
                <!-- Floating Product Image -->
                <div class="hero-image-wrapper animate-slide-up delay-300" style="animation: floatBubbles 6s ease-in-out infinite alternate; position: relative; z-index: 2;">
                    <!-- Using a high quality Pixabay image placeholder representing a premium device -->
                    <img src="https://cdn.pixabay.com/photo/2020/11/22/11/53/apple-5766388_1280.jpg" alt="Premium Tech" class="img-fluid rounded-4 shadow-elevated" style="border: 1px solid rgba(255,255,255,0.1); transform: perspective(1000px) rotateY(-15deg); box-shadow: -20px 20px 40px rgba(0,0,0,0.5), 0 0 40px rgba(124,58,237,0.3);">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promo Banners -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center border-0">
                    <div class="card-body">
                        <i class="fas fa-shipping-fast fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Free Shipping</h5>
                        <p class="card-text">On orders over $50</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center border-0">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Secure Payment</h5>
                        <p class="card-text">100% safe transactions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center border-0">
                    <div class="card-body">
                        <i class="fas fa-undo fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Easy Returns</h5>
                        <p class="card-text">30-day return policy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row">
            <?php foreach ($featured as $prod): ?>
            <div class="col-md-6 col-lg-4">
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo getProductImageUrl($prod['image'], $prod['product_name']); ?>" 
                             alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                        <?php if (!empty($prod['original_price']) && $prod['original_price'] > $prod['price']): ?>
                        <span class="product-badge">
                            -<?php echo round((($prod['original_price'] - $prod['price']) / $prod['original_price']) * 100); ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <h6 class="product-name"><?php echo htmlspecialchars($prod['product_name']); ?></h6>
                        <div class="product-rating">
                            <?php 
                            $rating = $prod['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<i class="fas fa-star ' . ($i <= $rating ? 'text-warning' : 'text-secondary') . '"></i>';
                            }
                            ?>
                            <small>(<?php echo $prod['review_count']; ?>)</small>
                        </div>
                        <div class="product-price">
                            <?php echo formatCurrency($prod['price']); ?>
                            <?php if (!empty($prod['original_price'])): ?>
                            <span class="product-original-price"><?php echo formatCurrency($prod['original_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-buttons">
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                            <button class="btn btn-outline-primary btn-sm wishlist-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <a href="pages/product-detail.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-outline-secondary btn-sm quick-buy-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Best Sellers</h2>
        <div class="row">
            <?php foreach ($bestsellers as $prod): ?>
            <div class="col-md-6 col-lg-4">
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo getProductImageUrl($prod['image'], $prod['product_name']); ?>" 
                             alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                        <?php if (!empty($prod['original_price']) && $prod['original_price'] > $prod['price']): ?>
                        <span class="product-badge">Hot</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <h6 class="product-name"><?php echo htmlspecialchars($prod['product_name']); ?></h6>
                        <div class="product-rating">
                            <?php 
                            $rating = $prod['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<i class="fas fa-star ' . ($i <= $rating ? 'text-warning' : 'text-secondary') . '"></i>';
                            }
                            ?>
                            <small>(<?php echo $prod['review_count']; ?>)</small>
                        </div>
                        <div class="product-price">
                            <?php echo formatCurrency($prod['price']); ?>
                            <?php if (!empty($prod['original_price'])): ?>
                            <span class="product-original-price"><?php echo formatCurrency($prod['original_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-buttons">
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                            <button class="btn btn-outline-primary btn-sm wishlist-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <a href="pages/product-detail.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-outline-secondary btn-sm quick-buy-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="row">
            <?php foreach ($new_arrivals as $prod): ?>
            <div class="col-md-6 col-lg-4">
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo getProductImageUrl($prod['image'], $prod['product_name']); ?>" 
                             alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                        <span class="product-badge">New</span>
                    </div>
                    <div class="product-body">
                        <h6 class="product-name"><?php echo htmlspecialchars($prod['product_name']); ?></h6>
                        <div class="product-rating">
                            <?php 
                            $rating = $prod['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<i class="fas fa-star ' . ($i <= $rating ? 'text-warning' : 'text-secondary') . '"></i>';
                            }
                            ?>
                            <small>(<?php echo $prod['review_count']; ?>)</small>
                        </div>
                        <div class="product-price">
                            <?php echo formatCurrency($prod['price']); ?>
                            <?php if (!empty($prod['original_price'])): ?>
                            <span class="product-original-price"><?php echo formatCurrency($prod['original_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-buttons">
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                            <button class="btn btn-outline-primary btn-sm wishlist-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <a href="pages/product-detail.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-outline-secondary btn-sm quick-buy-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="mb-3">Subscribe to Our Newsletter</h2>
                <p class="mb-4">Get exclusive deals and updates delivered to your inbox</p>
                <form action="api/newsletter.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        <button class="btn btn-warning" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
