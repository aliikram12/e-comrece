<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (isLoggedIn()) {
    redirect(SITE_URL);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $user = new User($conn);
        $result = $user->register($username, $email, $password, $first_name, $last_name);
        
        if ($result['success']) {
            $success = 'Registration successful! You can now <a href="login.php">login</a>.';
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Register - TechStore';
include '../includes/header.php';
?>

<div class="auth-page w-100">
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="auth-card border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus text-primary mb-3" style="font-size: 3rem;"></i>
                        <h2>Create Account</h2>
                        <p class="text-muted">Join TechStore to experience premium shopping</p>
                    </div>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                    <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #6ee7b7;">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John"
                                           value="<?php echo $_POST['first_name'] ?? ''; ?>">
                                    <label for="first_name">First Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe"
                                           value="<?php echo $_POST['last_name'] ?? ''; ?>">
                                    <label for="last_name">Last Name</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" required placeholder="johndoe"
                                   value="<?php echo $_POST['username'] ?? ''; ?>">
                            <label for="username"><i class="fas fa-at me-2 text-muted"></i>Username</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com"
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                            <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email Address</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                            <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                            <small class="text-muted ms-2 mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Minimum 6 characters</small>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm Password">
                            <label for="confirm_password"><i class="fas fa-lock me-2 text-muted"></i>Confirm Password</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                            Create Account <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                    
                    <hr class="my-4 border-secondary opacity-25">
                    
                    <p class="text-center text-muted mb-0">
                        Already have an account? <a href="login.php" class="text-primary text-decoration-none fw-bold">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
