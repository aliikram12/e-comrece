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

<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Login to Your Account</h2>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center text-muted mb-3">
                        Don't have an account? <a href="register.php">Register here</a>
                    </p>
                    
                    <p class="text-center text-muted">
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                    </p>
                </div>
            </div>
            
            <!-- Demo Credentials -->
            <div class="alert alert-info mt-4">
                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Demo Login</h6>
                <p class="mb-1"><strong>Email:</strong> demo@techstore.com</p>
                <p class="mb-0"><strong>Password:</strong> password123</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
