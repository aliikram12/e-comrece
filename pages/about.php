<?php
require_once '../includes/config.php';

$page_title = 'About Us - TechStore';

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">About Us</li>
        </ol>
    </nav>
    
    <h1 class="section-title mb-5">About TechStore</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <h2 class="mb-4 text-heading">Our Story</h2>
            <p>TechStore was founded with a simple mission: to provide customers with access to the latest technology products at affordable prices. Since our inception, we've grown to become one of the leading online retailers of premium tech products.</p>
            
            <h3 class="mt-4">Our Mission</h3>
            <p>We believe that everyone should have access to quality technology. Our mission is to make premium tech products affordable and accessible to everyone, regardless of their budget or location.</p>
            
            <h3 class="mt-4">Our Values</h3>
            <ul>
                <li><strong>Quality:</strong> We only stock products from trusted brands and manufacturers.</li>
                <li><strong>Affordability:</strong> We work hard to bring you the best prices without compromising on quality.</li>
                <li><strong>Customer Service:</strong> Your satisfaction is our top priority.</li>
                <li><strong>Innovation:</strong> We constantly update our inventory with the latest technology.</li>
                <li><strong>Reliability:</strong> We promise fast shipping and secure transactions.</li>
            </ul>
            
            <h3 class="mt-5 mb-4 text-heading">Why Choose Us?</h3>
            <div class="row mt-3">
                <div class="col-md-6 mb-4">
                    <div class="promo-card p-3 h-100">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-heading"><i class="fas fa-shipping-fast text-primary me-2"></i>Fast Shipping</h6>
                            <p class="card-text text-secondary mb-0">Free shipping on orders over $50. Fast delivery to your doorstep.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="promo-card p-3 h-100">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-heading"><i class="fas fa-shield-alt text-primary me-2"></i>Secure Payment</h6>
                            <p class="card-text text-secondary mb-0">100% secure payment gateway with multiple payment options.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="promo-card p-3 h-100">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-heading"><i class="fas fa-undo text-primary me-2"></i>Easy Returns</h6>
                            <p class="card-text text-secondary mb-0">30-day return policy. No questions asked.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="promo-card p-3 h-100">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-heading"><i class="fas fa-headset text-primary me-2"></i>24/7 Support</h6>
                            <p class="card-text text-secondary mb-0">Round-the-clock customer support to help you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="promo-card p-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold text-heading"><i class="fas fa-info-circle text-primary me-2"></i>Quick Facts</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Founded:</strong> <span>2024</span></li>
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Products:</strong> <span>1000+</span></li>
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Customers:</strong> <span>10000+</span></li>
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Brands:</strong> <span>100+</span></li>
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Regions:</strong> <span>Worldwide</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
