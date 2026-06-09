<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once __DIR__ . '/../classes/Category.php';
    require_once __DIR__ . '/../classes/Wishlist.php';
    require_once __DIR__ . '/../classes/Cart.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TechStore — Your one-stop destination for premium technology products at affordable prices.">
    <title><?php echo $page_title ?? 'TechStore'; ?></title>
    
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/responsive.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent sticky-top shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="<?php echo SITE_URL; ?>">
                <i class="fas fa-shopping-bag text-primary me-2"></i>TechStore
            </a>
            
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>pages/shop.php">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <?php 
                            $category = new Category($conn);
                            $categories = $category->getAllCategories();
                            foreach ($categories as $cat) {
                                echo '<li><a class="dropdown-item" href="' . SITE_URL . 'pages/shop.php?category=' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>pages/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>pages/contact.php">Contact</a>
                    </li>
                </ul>
            </div>
            
            <!-- Right side icons -->
            <div class="navbar-nav ms-3">
                <!-- Search -->
                <div class="nav-item dropdown">
                    <button class="btn btn-light me-2" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <!-- Wishlist -->
                <a href="<?php echo isLoggedIn() ? SITE_URL . 'pages/wishlist.php' : 'javascript:alert("Please login first")'; ?>" class="btn btn-light me-2 position-relative">
                    <i class="fas fa-heart"></i>
                    <?php if (isLoggedIn()) {
                        $wishlist = new Wishlist($conn);
                        $count = $wishlist->getWishlistCount($_SESSION['user_id']);
                        if ($count > 0) {
                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . $count . '</span>';
                        }
                    } ?>
                </a>
                
                <!-- Cart -->
                <a href="<?php echo SITE_URL; ?>pages/cart.php" class="btn btn-light me-2 position-relative cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php
                    $cart = new Cart($conn);
                    $count = $cart->getCartCount(isLoggedIn() ? $_SESSION['user_id'] : null);
                    ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" <?php echo $count > 0 ? '' : 'style="display: none;"'; ?>>
                        <?php echo $count > 0 ? $count : ''; ?>
                    </span>
                </a>
                
                <!-- User Menu -->
                <div class="nav-item dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (isLoggedIn()) { ?>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/orders.php">My Orders</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/addresses.php">Addresses</a></li>
                            <?php if (isAdmin()) { ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>admin/">Admin Panel</a></li>
                            <?php } ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>api/logout.php">Logout</a></li>
                        <?php } else { ?>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/login.php">Login</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/register.php">Register</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo SITE_URL; ?>pages/shop.php" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" placeholder="Search products..." required>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Content -->
    <main>
