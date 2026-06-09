<?php
require_once '../includes/config.php';

if (isLoggedIn() && isAdmin()) {
    redirect('dashboard.php');
}

redirect('login.php');
