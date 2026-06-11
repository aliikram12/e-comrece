<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'techstore');

// Site Configuration
define('SITE_NAME', 'TechStore');

// Dynamic SITE_URL detection — makes the project fully portable
// Works regardless of folder name, location, or server configuration
$_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// Detect the project root from the current script path
$_scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Walk up from subdirectories (pages/, admin/, api/) to find the project root
// The project root contains includes/config.php — we know __DIR__ points to includes/
$_projectRoot = str_replace('\\', '/', dirname(__DIR__));
$_docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$_basePath = str_replace($_docRoot, '', $_projectRoot);
$_basePath = '/' . trim($_basePath, '/') . '/';

define('SITE_URL', $_protocol . '://' . $_host . $_basePath);
define('ADMIN_URL', SITE_URL . 'admin/');

// Pagination
define('ITEMS_PER_PAGE', 12);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start Session
session_start();

// Timezone
date_default_timezone_set('UTC');

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
}

// Helper function to redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Helper function to sanitize input for DB
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Helper function to escape output for XSS prevention
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF Token generation
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token validation
function validateCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Helper function to format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

// Helper function to return a product image URL, local path prefix, or a remote fallback based on product name
function getProductImageUrl($image, $productName = '') {
    if (!empty($image)) {
        return strpos($image, 'http') === 0 ? $image : SITE_URL . ltrim($image, '/');
    }

    // Default high-quality tech fallback image from Pixabay
    return 'https://cdn.pixabay.com/photo/2014/05/02/21/50/laptop-336378_1280.jpg';
}

// Helper function to return a profile image URL or default avatar
function getProfileImageUrl($image) {
    if (!empty($image)) {
        return strpos($image, 'http') === 0 ? $image : SITE_URL . $image;
    }

    return 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80';
}

// Helper function to handle profile image uploads
function uploadProfileImage($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return array('success' => false, 'message' => 'No profile image uploaded.');
    }

    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes, true)) {
        return array('success' => false, 'message' => 'Only JPG, PNG and GIF profile images are allowed.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return array('success' => false, 'message' => 'Profile image must be smaller than 2MB.');
    }

    $uploadDir = __DIR__ . '/../assets/images/users/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'user_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . strtolower($extension);
    $destPath = $uploadDir . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return array('success' => false, 'message' => 'Unable to save profile image.');
    }

    return array('success' => true, 'path' => 'assets/images/users/' . $fileName);
}
?>
