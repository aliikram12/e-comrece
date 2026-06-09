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

<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Admin Login</h2>

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

                        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                    </form>

                    <hr class="my-4">

                    <?php if ($adminExists): ?>
                        <p class="text-center text-muted">If you do not have an admin account, ask your system administrator to create one.</p>
                    <?php else: ?>
                        <p class="text-center text-muted">No administrator account exists yet. <a href="register.php">Create the first admin</a>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';
