<?php
require_once '../includes/config.php';
require_once '../classes/Cart.php';

header('Content-Type: application/json');

$response = array('success' => false, 'message' => 'Error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($product_id <= 0 || $quantity <= 0) {
        $response['message'] = 'Invalid product or quantity';
        echo json_encode($response);
        exit;
    }
    
    $cart = new Cart($conn);
    $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
    $color = isset($_POST['color']) && !empty($_POST['color']) ? sanitize($_POST['color']) : null;
    
    if ($cart->addToCart($product_id, $quantity, $user_id, null, $color)) {
        $response['success'] = true;
        $response['message'] = 'Product added to cart';
        $response['cart_count'] = $cart->getCartCount($user_id);
    } else {
        $response['message'] = 'Failed to add to cart';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
