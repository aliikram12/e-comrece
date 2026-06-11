<div class="card admin-sidebar border-0 mb-4 auth-card">
    <div class="card-body p-4">
        <h5 class="card-title mb-4 d-flex align-items-center fw-bold">
            <i class="fas fa-shield-alt text-danger me-2"></i> Admin Panel
        </h5>
        <div class="list-group list-group-flush admin-nav">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent text-light border-0 mb-1 rounded <?php echo $currentPage === 'dashboard.php' ? 'active bg-danger text-white' : ''; ?>">
                <i class="fas fa-tachometer-alt me-2 w-20px"></i> Dashboard
            </a>
            <a href="products.php" class="list-group-item list-group-item-action bg-transparent text-light border-0 mb-1 rounded <?php echo $currentPage === 'products.php' || $currentPage === 'edit-product.php' ? 'active bg-danger text-white' : ''; ?>">
                <i class="fas fa-box me-2 w-20px"></i> Products
            </a>
            <a href="orders.php" class="list-group-item list-group-item-action bg-transparent text-light border-0 mb-1 rounded <?php echo $currentPage === 'orders.php' ? 'active bg-danger text-white' : ''; ?>">
                <i class="fas fa-shopping-cart me-2 w-20px"></i> Orders
            </a>
            <a href="users.php" class="list-group-item list-group-item-action bg-transparent text-light border-0 mb-1 rounded <?php echo $currentPage === 'users.php' || $currentPage === 'add-user.php' ? 'active bg-danger text-white' : ''; ?>">
                <i class="fas fa-users me-2 w-20px"></i> Users
            </a>
            <a href="profile.php" class="list-group-item list-group-item-action bg-transparent text-light border-0 mb-1 rounded <?php echo $currentPage === 'profile.php' ? 'active bg-danger text-white' : ''; ?>">
                <i class="fas fa-user-cog me-2 w-20px"></i> My Profile
            </a>
        </div>
    </div>
    <div class="card-body admin-sidebar-footer border-top border-secondary border-opacity-25 p-4">
        <h6 class="mb-3 text-muted text-uppercase small fw-bold">Quick Actions</h6>
        <a href="add-product.php" class="btn btn-sm btn-danger w-100 mb-2 rounded-pill shadow-sm">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
        <a href="users.php" class="btn btn-sm btn-outline-light w-100 mb-3 rounded-pill">
            <i class="fas fa-users-cog me-1"></i> Manage Users
        </a>
        <a href="<?php echo SITE_URL; ?>api/logout.php" class="btn btn-sm w-100 rounded-pill" style="background: rgba(239, 68, 68, 0.1); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3);">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</div>

<style>
.admin-nav .list-group-item {
    transition: all 0.2s ease;
}
.admin-nav .list-group-item:hover:not(.active) {
    background: rgba(255, 255, 255, 0.05) !important;
    transform: translateX(4px);
}
.w-20px { width: 20px; text-align: center; }
</style>
