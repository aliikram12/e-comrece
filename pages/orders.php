<?php
require_once '../includes/config.php';
require_once '../classes/Order.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . 'pages/login.php');
}

$page_title = 'My Orders - TechStore';

$order = new Order($conn);
$user_id = $_SESSION['user_id'];
$orders = $order->getUserOrders($user_id);

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">My Orders</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">My Orders</h2>
    
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-lg-3">
            <div class="list-group">
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
                <a href="orders.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
                <a href="addresses.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                </a>
                <a href="wishlist.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i>Wishlist
                </a>
                <a href="<?php echo SITE_URL; ?>api/logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Orders List -->
        <div class="col-lg-9">
            <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to place your first order</p>
                <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($ord['order_number']); ?></strong></td>
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
                                    <?php echo ucfirst(str_replace('_', ' ', $ord['order_status'])); ?>
                                </span>
                            </td>
                            <td><?php echo formatCurrency($ord['total_amount']); ?></td>
                            <td>
                                <a href="order-detail.php?order_id=<?php echo $ord['order_id']; ?>" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
