<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL);
}

$userClass = new User($conn);
$admin = $userClass->getUserById($_SESSION['user_id']);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $profile_image_path = null;

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = uploadProfileImage($_FILES['profile_image']);
        if ($uploadResult['success']) {
            $profile_image_path = $uploadResult['path'];
        } else {
            $error = $uploadResult['message'];
        }
    }

    if (empty($error)) {
        $result = $userClass->updateProfile($_SESSION['user_id'], $first_name, $last_name, $phone, $profile_image_path);
        if ($result['success']) {
            $success = $result['message'];
            $admin = $userClass->getUserById($_SESSION['user_id']);
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = 'Admin Profile - TechStore';
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
                    <h2>Admin Profile</h2>
                    <p class="text-muted">Update your admin profile details and avatar.</p>
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

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile" value="1">

                        <!-- Professional Profile Picture -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 col-lg-4 text-center">
                                <div class="profile-avatar-wrapper" onclick="document.getElementById('admin-profile-input').click()">
                                    <img id="admin-avatar-preview" src="<?php echo getProfileImageUrl($admin['profile_image'] ?? ''); ?>" alt="Admin Avatar">
                                    <div class="profile-avatar-overlay">
                                        <i class="fas fa-camera"></i>
                                        <span>Change Photo</span>
                                    </div>
                                    <div class="profile-avatar-badge admin-badge">
                                        <i class="fas fa-shield-alt" style="font-size: 0.6rem;"></i>
                                    </div>
                                </div>
                                <input type="file" class="d-none" id="admin-profile-input" name="profile_image" accept="image/*" onchange="previewProfileImage(this, 'admin-avatar-preview')">
                                <div class="profile-image-meta">
                                    <span class="hint">JPG, PNG or GIF — Max 2MB</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($admin['first_name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($admin['last_name']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewProfileImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../includes/footer.php'; ?>
