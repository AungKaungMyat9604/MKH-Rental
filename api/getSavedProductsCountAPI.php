<?php
session_start();
require 'dbinfo.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to count saved products for the user
    $query = "SELECT COUNT(*) as saved_count FROM saved_products WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'saved_count' => $data['saved_count']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
}
?>
