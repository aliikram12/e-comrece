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
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Orders</h6>
                    <h3 class="mb-0"><?php echo $total_orders; ?></h3>
                    <small class="text-success">Orders received</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Revenue</h6>
                    <h3 class="mb-0"><?php echo formatCurrency($revenue); ?></h3>
                    <small class="text-success">From completed orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Products</h6>
                    <h3 class="mb-0"><?php echo $total_products; ?></h3>
                    <small class="text-success">In inventory</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Users</h6>
                    <h3 class="mb-0"><?php echo $total_users; ?></h3>
                    <small class="text-success">Registered users</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $ord): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($ord['order_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($ord['first_name'] . ' ' . $ord['last_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($ord['created_at'])); ?></td>
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
                                        <span class="badge bg-<?php echo $status_class; ?>">
                                            <?php echo ucfirst($ord['order_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatCurrency($ord['total_amount']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
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
