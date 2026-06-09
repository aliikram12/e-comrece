<?php
require_once '../includes/config.php';
require_once '../classes/Cart.php';

header('Content-Type: application/json');

$cart = new Cart($conn);
$user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
$count = $cart->getCartCount($user_id);

echo json_encode(array('count' => $count));
?>
