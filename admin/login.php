<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (isLoggedIn() && isAdmin()) {
    redirect('dashboard.php');
}

$userClass = new User($conn);
$adminExists = $userClass->getAdminCount() > 0;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $result = $userClass->login($email, $password);

        if ($result['success']) {
            if (isAdmin()) {
                redirect('dashboard.php');
            }

            $userClass->logout();
            $error = 'Admin access only. Please login with an administrator account.';
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Admin Login - TechStore';
include '../includes/header.php';
?>

<div class="auth-page w-100" style="background: var(--bg-body);">
<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="auth-card border-0" style="border-top: 4px solid var(--danger-color);">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt text-danger mb-3" style="font-size: 3rem;"></i>
                        <h2>Admin Portal</h2>
                        <p class="text-muted">Secure access for authorized personnel only</p>
                    </div>

                    <?php if ($error): ?>
                    <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="admin@example.com" required
                                value="<?php echo $_POST['email'] ?? ''; ?>">
                            <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Admin Email</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill shadow-sm">
                            Authenticate <i class="fas fa-unlock-alt ms-2"></i>
                        </button>
                    </form>

                    <hr class="my-4 border-secondary opacity-25">

                    <?php if ($adminExists): ?>
                        <p class="text-center text-muted mb-0 small">If you do not have an admin account, ask your system administrator to create one.</p>
                    <?php else: ?>
                        <p class="text-center text-muted mb-0 small">No administrator account exists yet. <a href="register.php" class="text-danger">Create the first admin</a>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
