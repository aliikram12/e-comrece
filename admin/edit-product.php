<?php
require_once '../includes/config.php';
require_once '../classes/Product.php';
require_once '../classes/Category.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$product = new Product($conn);
$category = new Category($conn);
$categories = $category->getAllCategories();
$error = '';
$success = '';

$product_id = intval($_GET['id'] ?? 0);
if ($product_id <= 0) {
    redirect('products.php');
}

$productData = $product->getProductById($product_id);
if (!$productData) {
    redirect('products.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = sanitize($_POST['product_name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $original_price = floatval($_POST['original_price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $sku = sanitize($_POST['sku'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');
    $image = sanitize($_POST['image'] ?? '');

    if (empty($product_name) || $price <= 0 || $category_id <= 0) {
        $error = 'Product name, price, and category are required.';
    } elseif (!in_array($status, ['active', 'inactive', 'discontinued'], true)) {
        $error = 'Invalid product status.';
    } else {
        if ($product->updateProduct($product_id, $product_name, $description, $price, $original_price, $category_id, $stock_quantity, $sku, $status, $image)) {
            $success = 'Product updated successfully.';
            $productData = $product->getProductById($product_id);
        } else {
            $error = 'Unable to update product.';
        }
    }
}

$page_title = 'Edit Product - TechStore';
include '../includes/header.php';
?>

<div class="container-fluid py-4 admin-panel">
    <div class="row">
        <div class="col-xl-3 col-lg-4 mb-4">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="row mb-4">
                <div class="col-8">
                    <h2>Edit Product</h2>
                    <p class="text-muted">Update product details and inventory.</p>
                </div>
                <div class="col-4 text-end">
                    <a href="products.php" class="btn btn-secondary">Back to Products</a>
                </div>
            </div>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" required value="<?php echo htmlspecialchars($productData['product_name']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>" <?php echo intval($productData['category_id']) === $cat['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($productData['description']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo htmlspecialchars($productData['price']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Original Price</label>
                        <input type="number" step="0.01" name="original_price" class="form-control" value="<?php echo htmlspecialchars($productData['original_price']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?php echo htmlspecialchars($productData['stock_quantity']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="<?php echo htmlspecialchars($productData['sku']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Image URL</label>
                        <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($productData['image']); ?>">
                        <small class="form-text text-muted">Enter an online image URL or a local asset path.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['active', 'inactive', 'discontinued'] as $statusOption): ?>
                        <option value="<?php echo $statusOption; ?>" <?php echo $productData['status'] === $statusOption ? 'selected' : ''; ?>>
                            <?php echo ucfirst($statusOption); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php';
