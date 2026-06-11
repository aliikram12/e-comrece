<?php
require_once '../includes/config.php';

$page_title = 'Contact Us - TechStore';

$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // In a real application, you would save this to a database or send an email
        // For now, we'll just show a success message
        $message_sent = true;
    }
}

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Contact Us</li>
        </ol>
    </nav>
    
    <h1 class="section-title mb-5">Contact Us</h1>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="promo-card text-center p-4 h-100">
                <div class="card-body">
                    <i class="fas fa-map-marker-alt fa-3x text-primary mb-4" style="filter: drop-shadow(0 0 15px rgba(124, 58, 237, 0.4));"></i>
                    <h5 class="card-title fw-bold text-heading">Address</h5>
                    <p class="card-text text-secondary">123 Tech Street, Tech City, TC 12345</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="promo-card text-center p-4 h-100">
                <div class="card-body">
                    <i class="fas fa-phone fa-3x text-primary mb-4" style="filter: drop-shadow(0 0 15px rgba(124, 58, 237, 0.4));"></i>
                    <h5 class="card-title fw-bold text-heading">Phone</h5>
                    <p class="card-text text-secondary">+1 (555) 123-4567</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="promo-card text-center p-4 h-100">
                <div class="card-body">
                    <i class="fas fa-envelope fa-3x text-primary mb-4" style="filter: drop-shadow(0 0 15px rgba(124, 58, 237, 0.4));"></i>
                    <h5 class="card-title fw-bold text-heading">Email</h5>
                    <p class="card-text text-secondary">support@techstore.com</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <?php if ($message_sent): ?>
            <div class="alert alert-success mb-4" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #6ee7b7;">
                <i class="fas fa-check-circle me-2"></i>Thank you! Your message has been sent successfully. We'll get back to you soon.
            </div>
            <?php endif; ?>
            
            <h2 class="mb-4 text-heading">Send us a Message</h2>
            
            <div class="promo-card p-4">
                <form method="POST" class="needs-validation">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                        <label for="name"><i class="fas fa-user me-2 text-muted"></i>Name *</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email *</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                        <label for="subject"><i class="fas fa-tag me-2 text-muted"></i>Subject *</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <textarea class="form-control" id="message" name="message" placeholder="Message" style="height: 150px" required></textarea>
                        <label for="message"><i class="fas fa-comment me-2 text-muted"></i>Message *</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                        Send Message <i class="fas fa-paper-plane ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="promo-card p-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold text-heading"><i class="fas fa-clock text-primary me-2"></i>Business Hours</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Monday - Friday:</strong> <span>9:00 AM - 6:00 PM</span></li>
                        <li class="mb-3 d-flex justify-content-between"><strong class="text-secondary">Saturday:</strong> <span>10:00 AM - 4:00 PM</span></li>
                        <li class="mb-0 d-flex justify-content-between"><strong class="text-danger">Sunday:</strong> <span class="text-danger">Closed</span></li>
                    </ul>
                </div>
            </div>
            
            <div class="promo-card p-4 mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold text-heading"><i class="fas fa-hashtag text-primary me-2"></i>Follow Us</h5>
                    <div class="social-links d-flex gap-3">
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-info rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
