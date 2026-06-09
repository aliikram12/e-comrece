<?php
require_once '../includes/config.php';
require_once '../classes/User.php';

$user = new User($conn);
$user->logout();

redirect(SITE_URL . 'pages/login.php');
?>
