<?php
require_once '../includes/config.php';
require_once '../classes/Product.php';
require_once '../classes/Category.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Products - TechStore';

$product = new Product($conn);
$category = new Category($conn);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id'] ?? 0);
    if ($product_id > 0 && $product->deleteProduct($product_id)) {
        $success = 'Product deleted successfully.';
    } else {
        $error = 'Unable to delete product.';
    }
}

$page = max(1, intval($_GET['page'] ?? 1));
$products = $product->getAdminProducts($page, 20);
$total = $product->getTotalCount(null, false);
$total_pages = ceil($total / 20);

include '../includes/header.php';
?>

<div class="container-fluid py-4 admin-panel">
    <div class="row">
        <div class="col-xl-3 col-lg-4 mb-4">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="row mb-4">
                <div class="col-6">
                    <h2>Manage Products</h2>
                    <p class="text-muted">Add, edit, and remove products from the catalog.</p>
                </div>
                <div class="col-6 text-end">
                    <a href="add-product.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
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
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $prod): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($prod['product_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                            <td><?php echo formatCurrency($prod['price']); ?></td>
                            <td><?php echo intval($prod['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($prod['rating'] ?: '0'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $prod['status'] === 'active' ? 'success' : ($prod['status'] === 'inactive' ? 'secondary' : 'warning'); ?>">
                                    <?php echo ucfirst($prod['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit-product.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" class="d-inline-block" onsubmit="return confirm('Delete this product?');">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                    <button type="submit" name="delete_product" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
