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
    
    <h1 class="mb-4">Contact Us</h1>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Address</h5>
                    <p class="card-text">123 Tech Street, Tech City, TC 12345</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Phone</h5>
                    <p class="card-text">+1 (555) 123-4567</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Email</h5>
                    <p class="card-text">support@techstore.com</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <?php if ($message_sent): ?>
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>Thank you! Your message has been sent successfully. We'll get back to you soon.
            </div>
            <?php endif; ?>
            
            <h2 class="mb-4">Send us a Message</h2>
            
            <form method="POST" class="needs-validation">
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Subject *</label>
                    <input type="text" class="form-control" name="subject" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea class="form-control" name="message" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Business Hours</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</li>
                        <li class="mb-2"><strong>Saturday:</strong> 10:00 AM - 4:00 PM</li>
                        <li class="mb-2"><strong>Sunday:</strong> Closed</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Follow Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-primary me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-primary"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
