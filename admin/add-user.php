<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$userClass = new User($conn);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'customer');
    $status = sanitize($_POST['status'] ?? 'active');
    $profile_image_path = null;

    $validRoles = ['customer', 'admin', 'vendor'];
    $validStatuses = ['active', 'inactive', 'banned'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Username, email, and password are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!in_array($role, $validRoles, true) || !in_array($status, $validStatuses, true)) {
        $error = 'Invalid role or status.';
    } else {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadProfileImage($_FILES['profile_image']);
            if ($uploadResult['success']) {
                $profile_image_path = $uploadResult['path'];
            } else {
                $error = $uploadResult['message'];
            }
        }

        if (empty($error)) {
            $created = $userClass->registerWithRole($username, $email, $password, $first_name, $last_name, $role, $status);
            if ($created['success']) {
                $userClass->updateUser($created['user_id'], $username, $email, $first_name, $last_name, $phone, $role, $status, $profile_image_path);
                $success = 'User created successfully.';
            } else {
                $error = $created['message'];
            }
        }
    }
}

$page_title = 'Add User - TechStore';
include '../includes/header.php';
?>

<div class="container-fluid py-4 admin-panel">
    <div class="row">
        <div class="col-xl-3 col-lg-4 mb-4">
            <?php include 'sidebar.php'; ?>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="row mb-4">
                <div class="col-8">
                    <h2>Add User</h2>
                    <p class="text-muted">Create a new customer, vendor, or admin account.</p>
                </div>
                <div class="col-4 text-end">
                    <a href="users.php" class="btn btn-secondary">Back to Users</a>
                </div>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <?php foreach (['customer', 'vendor', 'admin'] as $roleOption): ?>
                                    <option value="<?php echo $roleOption; ?>" <?php echo ($_POST['role'] ?? 'customer') === $roleOption ? 'selected' : ''; ?>><?php echo ucfirst($roleOption); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <?php foreach (['active', 'inactive', 'banned'] as $statusOption): ?>
                                    <option value="<?php echo $statusOption; ?>" <?php echo ($_POST['status'] ?? 'active') === $statusOption ? 'selected' : ''; ?>><?php echo ucfirst($statusOption); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';
