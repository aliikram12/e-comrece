<?php
require_once '../includes/config.php';
require_once '../classes/Product.php';
require_once '../classes/Category.php';

$page_title = 'Shop - TechStore';

$product = new Product($conn);
$category = new Category($conn);

// Get current page
$page = $_GET['page'] ?? 1;
$page = max(1, intval($page));

// Get filters
$category_id = $_GET['category'] ?? null;
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$price_min = $_GET['price_min'] ?? 0;
$price_max = $_GET['price_max'] ?? 10000;

// Get products based on filters
if (!empty($search)) {
    $products = $product->searchProducts($search, $page, ITEMS_PER_PAGE);
    $total = $product->getTotalCount();
} elseif (!empty($category_id)) {
    $products = $product->getProductsByCategory($category_id, $page, ITEMS_PER_PAGE);
    $total = $product->getTotalCount($category_id);
} else {
    $products = $product->getAllProducts($page, ITEMS_PER_PAGE);
    $total = $product->getTotalCount();
}

$categories = $category->getAllCategories();
$total_pages = ceil($total / ITEMS_PER_PAGE);

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Shop</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">Our Products</h2>
    
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="filters-sidebar">
                <h5 class="mb-4">Filters</h5>
                
                <!-- Categories -->
                <div class="filter-group">
                    <h6>Categories</h6>
                    <div class="filter-option">
                        <input type="checkbox" id="cat_all" value="" 
                               <?php echo empty($category_id) ? 'checked' : ''; ?>
                               onchange="filterProducts()">
                        <label for="cat_all">All Categories</label>
                    </div>
                    <?php foreach ($categories as $cat): ?>
                    <div class="filter-option">
                        <input type="checkbox" id="cat_<?php echo $cat['category_id']; ?>" 
                               value="<?php echo $cat['category_id']; ?>"
                               <?php echo ($category_id == $cat['category_id']) ? 'checked' : ''; ?>
                               onchange="filterProducts()">
                        <label for="cat_<?php echo $cat['category_id']; ?>">
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Price Range -->
                <div class="filter-group">
                    <h6>Price Range</h6>
                    <input type="range" class="form-range" min="0" max="10000" 
                           value="<?php echo $price_max; ?>" id="priceRange" onchange="filterProducts()">
                    <small>Up to $<span id="priceValue"><?php echo $price_max; ?></span></small>
                </div>
                
                <!-- Sort -->
                <div class="filter-group">
                    <h6>Sort By</h6>
                    <select class="form-select" id="sortSelect" onchange="filterProducts()">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="price-low" <?php echo $sort == 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-high" <?php echo $sort == 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="bestselling" <?php echo $sort == 'bestselling' ? 'selected' : ''; ?>>Best Selling</option>
                    </select>
                </div>
                
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">Reset Filters</button>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted">
                    Showing <strong><?php echo min(ITEMS_PER_PAGE, count($products)); ?></strong> 
                    of <strong><?php echo $total; ?></strong> products
                </p>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            
            <!-- Products -->
            <div class="row">
                <?php if (empty($products)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p>No products found. Try adjusting your filters.</p>
                    </div>
                </div>
                <?php else: ?>
                    <?php foreach ($products as $prod): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
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
                                <div class="mb-2">
                                    <select class="form-select form-select-sm color-select" name="color">
                                        <option value="">Select Color</option>
                                        <option value="Gray">Gray</option>
                                        <option value="Black">Black</option>
                                        <option value="Dusty Gold">Dusty Gold</option>
                                    </select>
                                </div>
                                <div class="product-buttons">
                                    <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                        <i class="fas fa-shopping-cart"></i> Add
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm wishlist-btn" data-product-id="<?php echo $prod['product_id']; ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <a href="product-detail.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-outline-secondary btn-sm quick-buy-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1<?php echo !empty($category_id) ? '&category=' . $category_id : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">First</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($category_id) ? '&category=' . $category_id : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($category_id) ? '&category=' . $category_id : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($category_id) ? '&category=' . $category_id : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Next</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $total_pages; ?><?php echo !empty($category_id) ? '&category=' . $category_id : ''; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Last</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const SITE_URL = '<?php echo SITE_URL; ?>';

function filterProducts() {
    // Get filter values
    const categorySelect = document.getElementById('cat_all');
    let categoryId = '';
    
    if (!categorySelect.checked) {
        for (let i = 1; i <= 100; i++) {
            const checkbox = document.getElementById('cat_' + i);
            if (checkbox && checkbox.checked) {
                categoryId = checkbox.value;
                break;
            }
        }
    }
    
    const priceMax = document.getElementById('priceRange').value;
    const sort = document.getElementById('sortSelect').value;
    
    let url = '?';
    if (categoryId) url += 'category=' + categoryId + '&';
    url += 'price_max=' + priceMax + '&sort=' + sort;
    
    window.location.href = url;
}

function resetFilters() {
    window.location.href = '?';
}

// Update price display
document.getElementById('priceRange')?.addEventListener('change', function() {
    document.getElementById('priceValue').textContent = this.value;
});
</script>

<?php include '../includes/footer.php'; ?>
