<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$response = array('success' => false, 'message' => 'Error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email';
        echo json_encode($response);
        exit;
    }
    
    // In a real application, you would save this to a database
    // For now, we'll just show success
    $response['success'] = true;
    $response['message'] = 'Thank you for subscribing!';
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
