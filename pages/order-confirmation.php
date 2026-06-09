<?php
require_once '../includes/config.php';

$page_title = 'Order Confirmation - TechStore';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    redirect(SITE_URL);
}

include '../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                    <h2 class="mb-3">Thank You for Your Order!</h2>
                    <p class="text-muted mb-4">Your order has been successfully placed and will be processed shortly.</p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-0">Order ID: <strong>#<?php echo htmlspecialchars($order_id); ?></strong></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>What's Next?</h6>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check me-2 text-success"></i> Order Confirmed</li>
                                <li><i class="fas fa-hourglass-half me-2 text-warning"></i> Processing</li>
                                <li><i class="fas fa-truck me-2 text-muted"></i> Shipped</li>
                                <li><i class="fas fa-box me-2 text-muted"></i> Delivered</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Next Steps</h6>
                            <ul class="list-unstyled text-start">
                                <li>✓ You'll receive a confirmation email</li>
                                <li>✓ We'll notify you when item ships</li>
                                <li>✓ Track your package online</li>
                                <li>✓ Contact support if needed</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo SITE_URL; ?>pages/orders.php" class="btn btn-primary">View My Orders</a>
                        <a href="<?php echo SITE_URL; ?>pages/shop.php" class="btn btn-outline-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
