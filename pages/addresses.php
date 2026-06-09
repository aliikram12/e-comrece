<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . 'pages/login.php');
}

$page_title = 'My Addresses - TechStore';

$user_id = $_SESSION['user_id'];

// Get saved addresses
$query = "SELECT * FROM addresses WHERE user_id = $user_id ORDER BY is_default DESC";
$result = $conn->query($query);
$addresses = $result->fetch_all(MYSQLI_ASSOC);

// Add new address
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $street = sanitize($_POST['street_address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $postal = sanitize($_POST['postal_code']);
    $country = sanitize($_POST['country']);
    $type = sanitize($_POST['address_type']);
    
    $query = "INSERT INTO addresses (user_id, street_address, city, state_province, postal_code, country, address_type) 
              VALUES ($user_id, '$street', '$city', '$state', '$postal', '$country', '$type')";
    
    if ($conn->query($query)) {
        $message = '<div class="alert alert-success">Address added successfully!</div>';
        redirect(SITE_URL . 'pages/addresses.php');
    }
}

// Delete address
if (isset($_GET['delete'])) {
    $address_id = intval($_GET['delete']);
    $query = "DELETE FROM addresses WHERE address_id = $address_id AND user_id = $user_id";
    $conn->query($query);
    redirect(SITE_URL . 'pages/addresses.php');
}

include '../includes/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Addresses</li>
        </ol>
    </nav>
    
    <h2 class="mb-4">My Addresses</h2>
    
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-lg-3">
            <div class="list-group">
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
                <a href="orders.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
                <a href="addresses.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                </a>
                <a href="wishlist.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i>Wishlist
                </a>
                <a href="<?php echo SITE_URL; ?>api/logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Addresses -->
        <div class="col-lg-9">
            <?php echo $message; ?>
            
            <div class="row mb-4">
                <?php if (empty($addresses)): ?>
                <div class="col-12">
                    <p class="text-muted">No saved addresses yet.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <?php echo ucfirst($addr['address_type']); ?> Address
                                    <?php if ($addr['is_default']): ?>
                                    <span class="badge bg-success ms-2">Default</span>
                                    <?php endif; ?>
                                </h6>
                                <p class="card-text">
                                    <strong><?php echo htmlspecialchars($addr['first_name'] . ' ' . $addr['last_name']); ?></strong><br>
                                    <?php echo htmlspecialchars($addr['street_address']); ?><br>
                                    <?php echo htmlspecialchars($addr['city'] . ', ' . $addr['state_province'] . ' ' . $addr['postal_code']); ?><br>
                                    <?php echo htmlspecialchars($addr['country']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($addr['phone']); ?></small>
                                </p>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <a href="?delete=<?php echo $addr['address_id']; ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Delete this address?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Add New Address Form -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Address</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="add_address" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">Address Type</label>
                            <select class="form-select" name="address_type" required>
                                <option value="shipping">Shipping Address</option>
                                <option value="billing">Billing Address</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Street Address *</label>
                            <input type="text" class="form-control" name="street_address" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State/Province *</label>
                                <input type="text" class="form-control" name="state" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" class="form-control" name="postal_code" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country *</label>
                                <input type="text" class="form-control" name="country" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
