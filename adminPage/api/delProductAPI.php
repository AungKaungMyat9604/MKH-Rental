<?php
session_start();
require 'dbinfo.php'; // Include your database connection script

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($product_id > 0) {
        // Prepare the SQL statement to delete the product
        $query = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $product_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully';
        } else {
            $response['message'] = 'Failed to delete product: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Invalid product ID';
    }

    $conn->close();
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
