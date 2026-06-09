<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'techstore');

// Site Configuration
define('SITE_NAME', 'TechStore');
define('SITE_URL', 'http://localhost/e-comrece/');
define('ADMIN_URL', 'http://localhost/e-comrece/admin/');

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

// Helper function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
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

    if (!empty($productName)) {
        $productName = strtolower($productName);
        $keywords = [
            'iphone', 'android', 'smartphone', 'laptop', 'notebook', 'macbook', 'tablet',
            'headphone', 'earbuds', 'watch', 'camera', 'lens', 'speaker', 'console',
            'monitor', 'keyboard', 'mouse', 'printer', 'drone', 'router', 'software', 'gaming'
        ];

        foreach ($keywords as $keyword) {
            if (strpos($productName, $keyword) !== false) {
                return 'https://source.unsplash.com/featured/800x600?' . rawurlencode($keyword . ' technology');
            }
        }

        return 'https://source.unsplash.com/featured/800x600?' . rawurlencode($productName . ' technology');
    }

    return 'https://source.unsplash.com/featured/800x600?technology';
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
