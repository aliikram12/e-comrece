<?php
require_once '../includes/config.php';
require_once '../classes/Wishlist.php';

header('Content-Type: application/json');

$response = array('success' => false, 'message' => 'Error');

if (!isLoggedIn()) {
    $response['message'] = 'Please login to use wishlist';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($product_id <= 0) {
        $response['message'] = 'Invalid product';
        echo json_encode($response);
        exit;
    }
    
    $wishlist = new Wishlist($conn);
    $user_id = $_SESSION['user_id'];
    
    // Check if already in wishlist
    if ($wishlist->isInWishlist($user_id, $product_id)) {
        // Remove from wishlist
        if ($wishlist->removeFromWishlist($user_id, $product_id)) {
            $response['success'] = true;
            $response['message'] = 'Removed from wishlist';
            $response['action'] = 'removed';
        }
    } else {
        // Add to wishlist
        $result = $wishlist->addToWishlist($user_id, $product_id);
        if ($result['success']) {
            $response['success'] = true;
            $response['message'] = $result['message'];
            $response['action'] = 'added';
        } else {
            $response['message'] = $result['message'];
        }
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
