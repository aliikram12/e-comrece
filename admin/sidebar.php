<div class="card admin-sidebar shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">Admin Panel</h5>
        <div class="list-group list-group-flush">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <a href="dashboard.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="products.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">Products</a>
            <a href="orders.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'orders.php' ? 'active' : ''; ?>">Orders</a>
            <a href="users.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">Users</a>
            <a href="profile.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">My Profile</a>
            <a href="add-product.php" class="list-group-item list-group-item-action <?php echo $currentPage === 'add-product.php' ? 'active' : ''; ?>">Add Product</a>
        </div>
    </div>
    <div class="card-body admin-sidebar-footer">
        <h6 class="mb-3">Quick Actions</h6>
        <a href="add-product.php" class="btn btn-sm btn-primary w-100 mb-2">Add Product</a>
        <a href="users.php" class="btn btn-sm btn-outline-primary w-100 mb-3">Manage Users</a>
        <a href="<?php echo SITE_URL; ?>api/logout.php" class="btn btn-sm btn-outline-danger w-100">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</div>
