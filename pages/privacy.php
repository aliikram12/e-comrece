<?php
require_once '../includes/config.php';

$page_title = 'Privacy Policy - TechStore';

include '../includes/header.php';
?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Privacy Policy</li>
        </ol>
    </nav>
    
    <h1 class="mb-4">Privacy Policy</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <h2>1. Information We Collect</h2>
            <p>We collect personal information you provide directly to us, such as:</p>
            <ul>
                <li>Name, email address, and phone number</li>
                <li>Shipping and billing addresses</li>
                <li>Payment information (processed securely)</li>
                <li>Account credentials and preferences</li>
            </ul>
            
            <h2 class="mt-4">2. How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Process orders and send order updates</li>
                <li>Provide customer support</li>
                <li>Personalize your shopping experience</li>
                <li>Send marketing emails (with your consent)</li>
                <li>Improve our website and services</li>
            </ul>
            
            <h2 class="mt-4">3. Data Security</h2>
            <p>We implement industry-standard security measures to protect your information:</p>
            <ul>
                <li>SSL encryption for all transactions</li>
                <li>Secure password hashing</li>
                <li>Regular security audits</li>
                <li>Limited staff access to personal data</li>
            </ul>
            
            <h2 class="mt-4">4. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal information</li>
                <li>Request corrections or deletions</li>
                <li>Opt-out of marketing communications</li>
                <li>Request data portability</li>
            </ul>
            
            <h2 class="mt-4">5. Cookies</h2>
            <p>We use cookies to enhance your browsing experience. You can disable cookies in your browser settings.</p>
            
            <h2 class="mt-4">6. Contact Us</h2>
            <p>If you have questions about our privacy practices, please contact us at privacy@techstore.com</p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
