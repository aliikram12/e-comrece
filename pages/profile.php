<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . 'pages/login.php');
}

$page_title = 'My Profile - TechStore';

$user = new User($conn);
$user_data = $user->getUserById($_SESSION['user_id']);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $phone = sanitize($_POST['phone']);
        $profile_image_path = null;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadProfileImage($_FILES['profile_image']);
            if ($uploadResult['success']) {
                $profile_image_path = $uploadResult['path'];
            } else {
                $error = $uploadResult['message'];
            }
        }

        if (!$error) {
            $result = $user->updateProfile($_SESSION['user_id'], $first_name, $last_name, $phone, $profile_image_path);
            
            if ($result['success']) {
                $success = $result['message'];
                $user_data = $user->getUserById($_SESSION['user_id']);
            } else {
                $error = $result['message'];
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } elseif (strlen($new_password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            $result = $user->changePassword($_SESSION['user_id'], $old_password, $new_password);
            
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">My Profile</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">My Account</h2>
    
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-lg-3">
            <div class="list-group">
                <a href="profile.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
                <a href="orders.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
                <a href="addresses.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                </a>
                <a href="wishlist.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i>Wishlist
                </a>
                <?php if (isAdmin()): ?>
                <a href="<?php echo SITE_URL; ?>admin/dashboard.php" class="list-group-item list-group-item-action text-primary" style="font-weight: 600;">
                    <i class="fas fa-shield-alt me-2"></i>Admin Dashboard
                </a>
                <?php endif; ?>
                <a href="<?php echo SITE_URL; ?>api/logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Update Profile -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 col-lg-4 text-center">
                                <div class="profile-avatar-wrapper" onclick="document.getElementById('user-profile-input').click()">
                                    <img id="user-avatar-preview" src="<?php echo getProfileImageUrl($user_data['profile_image'] ?? ''); ?>" alt="Profile Image">
                                    <div class="profile-avatar-overlay">
                                        <i class="fas fa-camera"></i>
                                        <span>Change Photo</span>
                                    </div>
                                    <div class="profile-avatar-badge online">
                                        <i class="fas fa-check" style="font-size: 0.6rem;"></i>
                                    </div>
                                </div>
                                <input type="file" class="d-none" id="user-profile-input" name="profile_image" accept="image/*" onchange="previewProfileImage(this, 'user-avatar-preview')">
                                <div class="profile-image-meta">
                                    <span class="hint">JPG, PNG or GIF — Max 2MB</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="change_password" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" class="form-control" name="old_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
