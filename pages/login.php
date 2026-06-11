<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (isLoggedIn()) {
    redirect(SITE_URL);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $user = new User($conn);
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            redirect(SITE_URL . 'pages/profile.php');
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Login - TechStore';
include '../includes/header.php';
?>

<div class="auth-page w-100">
<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="auth-card border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle text-primary mb-3" style="font-size: 3rem;"></i>
                        <h2>Welcome Back</h2>
                        <p class="text-muted">Sign in to continue to TechStore</p>
                    </div>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                            <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email Address</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-primary text-decoration-none small">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                            Sign In <i class="fas fa-sign-in-alt ms-2"></i>
                        </button>
                    </form>
                    
                    <hr class="my-4 border-secondary opacity-25">
                    
                    <p class="text-center text-muted mb-0">
                        Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Register here</a>
                    </p>
                </div>
            </div>
            
            <!-- Demo Credentials -->
            <div class="alert alert-info mt-4" style="background: rgba(14, 165, 233, 0.1); border-color: rgba(14, 165, 233, 0.3); color: #7dd3fc; border-radius: var(--radius-md);">
                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Demo Login</h6>
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-0 small"><strong>User:</strong> demo@techstore.com</p>
                        <p class="mb-0 small"><strong>Pass:</strong> password123</p>
                    </div>
                    <div>
                        <p class="mb-0 small"><strong>Admin:</strong> admin@techstore.com</p>
                        <p class="mb-0 small"><strong>Pass:</strong> admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
