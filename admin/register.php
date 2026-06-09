<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

$userClass = new User($conn);
$adminCount = $userClass->getAdminCount();
$error = '';
$success = '';

if ($adminCount > 0 && (!isLoggedIn() || !isAdmin())) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Username, email and password are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        $result = $userClass->registerWithRole($username, $email, $password, $first_name, $last_name, 'admin');

        if ($result['success']) {
            $success = 'Administrator account created successfully. You can now <a href="login.php">login</a>.';
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Admin Registration - TechStore';
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Admin Registration</h2>

                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="<?php echo $_POST['first_name'] ?? ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="<?php echo $_POST['last_name'] ?? ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required
                                value="<?php echo $_POST['username'] ?? ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Create Admin Account</button>
                    </form>

                    <?php if ($adminCount > 0): ?>
                        <hr class="my-4">
                        <p class="text-center text-muted">Already have an admin account? <a href="login.php">Login here</a>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';
