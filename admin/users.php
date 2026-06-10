<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$userClass = new User($conn);
$error = '';
$success = '';
$edit_user = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $user_id = intval($_POST['user_id'] ?? 0);
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $first_name = sanitize($_POST['first_name'] ?? '');
        $last_name = sanitize($_POST['last_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $role = sanitize($_POST['role'] ?? 'customer');
        $status = sanitize($_POST['status'] ?? 'active');
        $profile_image_path = null;

        $validRoles = ['customer', 'admin', 'vendor'];
        $validStatuses = ['active', 'inactive', 'banned'];

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadProfileImage($_FILES['profile_image']);
            if ($uploadResult['success']) {
                $profile_image_path = $uploadResult['path'];
            } else {
                $error = $uploadResult['message'];
            }
        }

        if (empty($error)) {
            if (empty($username) || empty($email)) {
                $error = 'Username and email are required.';
            } elseif (!in_array($role, $validRoles, true) || !in_array($status, $validStatuses, true)) {
                $error = 'Invalid user role or status.';
            } elseif ($user_id <= 0) {
                $error = 'Invalid user selected.';
            } else {
                if ($userClass->updateUser($user_id, $username, $email, $first_name, $last_name, $phone, $role, $status, $profile_image_path)) {
                    $success = 'User updated successfully.';
                    if ($edit_user && $edit_user['user_id'] === $user_id) {
                        $edit_user = $userClass->getUserById($user_id);
                    }
                } else {
                    $error = 'Unable to update user.';
                }
            }
        }
    } elseif (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id'] ?? 0);

        if ($user_id === $_SESSION['user_id']) {
            $error = 'You cannot delete your own admin account while logged in.';
        } elseif ($userClass->deleteUser($user_id)) {
            $success = 'User deleted successfully.';
        } else {
            $error = 'Unable to delete user.';
        }
    }
}

$page = max(1, intval($_GET['page'] ?? 1));
$users = $userClass->getAllUsers($page, 20);
$total = $userClass->getTotalUsers();
$total_pages = ceil($total / 20);
$edit_user = null;

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_user = $userClass->getUserById($edit_id);
}

$page_title = 'Manage Users - TechStore';
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
                    <h2>Manage Users</h2>
                    <p class="text-muted">View, update, and remove customer and vendor accounts.</p>
                </div>
                <div class="col-4 text-end">
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if ($edit_user): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-4">Edit User: <?php echo htmlspecialchars($edit_user['username']); ?></h5>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="user_id" value="<?php echo $edit_user['user_id']; ?>">
                <div class="row">
                    <div class="col-md-6 mb-3 text-center">
                        <img src="<?php echo getProfileImageUrl($edit_user['profile_image'] ?? ''); ?>" alt="User Avatar" class="img-fluid rounded-circle mb-3" style="width: 130px; height: 130px; object-fit: cover;">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($edit_user['username']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($edit_user['first_name']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($edit_user['last_name']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($edit_user['phone']); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                            <?php foreach (['customer', 'vendor', 'admin'] as $roleOption): ?>
                            <option value="<?php echo $roleOption; ?>" <?php echo $edit_user['role'] === $roleOption ? 'selected' : ''; ?>><?php echo ucfirst($roleOption); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <?php foreach (['active', 'inactive', 'banned'] as $statusOption): ?>
                            <option value="<?php echo $statusOption; ?>" <?php echo $edit_user['status'] === $statusOption ? 'selected' : ''; ?>><?php echo ucfirst($statusOption); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" name="update_user" class="btn btn-primary">Save Changes</button>
                <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><img src="<?php echo getProfileImageUrl($user['profile_image'] ?? ''); ?>" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                            <td><?php echo ucfirst($user['status']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <form method="POST" class="d-inline-block" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="delete_user" value="1">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';
