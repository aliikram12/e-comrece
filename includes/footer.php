    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-shopping-bag text-warning me-2"></i>TechStore
                    </h5>
                    <p class="small">Your one-stop destination for premium tech products at affordable prices.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/shop.php" class="text-white-50 text-decoration-none">Shop</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/about.php" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/contact.php" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/faq.php" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">Support</h5>
                    <ul class="list-unstyled small">
                        <li><a href="<?php echo SITE_URL; ?>pages/privacy.php" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/terms.php" class="text-white-50 text-decoration-none">Terms & Conditions</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/returns.php" class="text-white-50 text-decoration-none">Returns Policy</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/shipping.php" class="text-white-50 text-decoration-none">Shipping Info</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">Newsletter</h5>
                    <p class="small text-white-50">Subscribe to get special offers and updates!</p>
                    <form action="<?php echo SITE_URL; ?>api/newsletter.php" method="POST">
                        <div class="input-group mb-2">
                            <input type="email" class="form-control" name="email" placeholder="Your email" required>
                            <button class="btn btn-warning" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="bg-white-50">
            
            <!-- Bottom -->
            <div class="row">
                <div class="col-md-6 small text-white-50">
                    <p>&copy; 2024 TechStore. All rights reserved.</p>
                </div>
                <div class="col-md-6 small text-white-50 text-md-end">
                    <p>Payment Methods: <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i> <i class="fab fa-cc-paypal"></i></p>
                </div>
            </div>
        </div>
    </footer>
    
    

    <!-- Pass PHP constants to JavaScript -->
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>assets/js/main.js?v=<?php echo time(); ?>"></script>
    
    <?php if (isset($extra_js)) { echo $extra_js; } ?>
</body>
</html>
