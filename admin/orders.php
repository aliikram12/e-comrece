<?php
require_once '../includes/config.php';
require_once '../classes/Order.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Orders - TechStore';

$order = new Order($conn);

$page = $_GET['page'] ?? 1;
$orders = $order->getAllOrders($page, 20);
$total = $order->getTotalOrders();
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
                <div class="col-12">
                    <h2>Manage Orders</h2>
                </div>
            </div>
    
    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($ord['order_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($ord['first_name'] . ' ' . $ord['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($ord['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($ord['created_at'])); ?></td>
                            <td><?php echo formatCurrency($ord['total_amount']); ?></td>
                            <td>
                                <?php
                                $order_status_class = match($ord['order_status']) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $order_status_class; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $ord['order_status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $payment_status_class = match($ord['payment_status']) {
                                    'pending' => 'warning',
                                    'completed' => 'success',
                                    'failed' => 'danger',
                                    'refunded' => 'info',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $payment_status_class; ?>">
                                    <?php echo ucfirst($ord['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order-detail.php?id=<?php echo $ord['order_id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
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
