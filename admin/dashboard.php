<?php
require_once '../includes/config.php';
require_once '../classes/Order.php';
require_once '../classes/Product.php';
require_once '../classes/User.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$page_title = 'Admin Dashboard - TechStore';

$order = new Order($conn);
$product = new Product($conn);
$user = new User($conn);

// Get dashboard stats
$total_orders = $order->getTotalOrders();
$revenue = $order->getSalesRevenue();
$total_products = $product->getTotalCount(null, false);
$total_users = $user->getTotalUsers();

// Get recent orders
$recent_orders = $order->getAllOrders(1, 10);

include '../includes/header.php';
?>

<div class="container-fluid py-4 admin-panel">
    <div class="row">
        <div class="col-xl-3 col-lg-4 mb-4">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="row mb-4">
                <div class="col-12">
                    <h2>Admin Dashboard</h2>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                </div>
            </div>
    
    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="metric-card shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted text-uppercase fw-bold mb-0">Total Orders</h6>
                    <i class="fas fa-shopping-bag metric-icon"></i>
                </div>
                <h2 class="mb-1 fw-bold"><?php echo $total_orders; ?></h2>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>Orders received</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted text-uppercase fw-bold mb-0">Total Revenue</h6>
                    <i class="fas fa-dollar-sign metric-icon text-success"></i>
                </div>
                <h2 class="mb-1 fw-bold"><?php echo formatCurrency($revenue); ?></h2>
                <small class="text-success"><i class="fas fa-chart-line me-1"></i>From completed orders</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted text-uppercase fw-bold mb-0">Total Products</h6>
                    <i class="fas fa-box-open metric-icon text-warning"></i>
                </div>
                <h2 class="mb-1 fw-bold"><?php echo $total_products; ?></h2>
                <small class="text-secondary"><i class="fas fa-warehouse me-1"></i>In inventory</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-muted text-uppercase fw-bold mb-0">Total Users</h6>
                    <i class="fas fa-users metric-icon text-info"></i>
                </div>
                <h2 class="mb-1 fw-bold"><?php echo $total_users; ?></h2>
                <small class="text-secondary"><i class="fas fa-user-plus me-1"></i>Registered users</small>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="auth-card border-0 p-0">
                <div class="card-header border-0 bg-transparent p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    <a href="orders.php" class="btn btn-sm btn-outline-light rounded-pill">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark mb-0 align-middle">
                            <thead style="background: rgba(255,255,255,0.05);">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th class="pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $ord): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td class="ps-4"><strong class="text-primary-light">#<?php echo htmlspecialchars($ord['order_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($ord['first_name'] . ' ' . $ord['last_name']); ?></td>
                                    <td class="text-muted"><?php echo date('M d, Y', strtotime($ord['created_at'])); ?></td>
                                    <td>
                                        <?php
                                        $status_class = match($ord['order_status']) {
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge rounded-pill bg-<?php echo $status_class; ?> bg-opacity-25 text-<?php echo $status_class; ?> border border-<?php echo $status_class; ?> border-opacity-50 px-3 py-2">
                                            <?php echo ucfirst($ord['order_status']); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?php echo formatCurrency($ord['total_amount']); ?></td>
                                    <td class="pe-4">
                                        <button class="btn btn-sm btn-outline-light rounded-pill px-3">View</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
