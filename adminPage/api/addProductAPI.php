<?php
session_start();
require 'dbinfo.php'; // Include your database connection script

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
    $cost_per_unit = isset($_POST['cost_per_unit']) && $_POST['cost_per_unit'] !== '' ? $_POST['cost_per_unit'] : NULL;
    $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $ton = isset($_POST['ton']) && $_POST['ton'] !== '' ? $_POST['ton'] : NULL;
    $arm_length = isset($_POST['arm_length']) && $_POST['arm_length'] !== '' ? $_POST['arm_length'] : NULL;
    $bucket_size = isset($_POST['bucket_size']) && $_POST['bucket_size'] !== '' ? $_POST['bucket_size'] : NULL;
    $dimension = isset($_POST['dimension']) && $_POST['dimension'] !== '' ? $_POST['dimension'] : NULL;
    $fuel_tank_capacity = isset($_POST['fuel_tank_capacity']) && $_POST['fuel_tank_capacity'] !== '' ? $_POST['fuel_tank_capacity'] : NULL;
    $weight = isset($_POST['weight']) && $_POST['weight'] !== '' ? $_POST['weight'] : NULL;
    $capacity_size = isset($_POST['capacity_size']) && $_POST['capacity_size'] !== '' ? $_POST['capacity_size'] : NULL;
    $compatible_excavator = isset($_POST['compatible_excavator']) ? $_POST['compatible_excavator'] : '';
    $usage_conditions = isset($_POST['usage_conditions']) ? $_POST['usage_conditions'] : '';

    // Image upload handling
    $imgData = null;
    $imgType = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];

        // Get file properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowed)) {
            if ($fileSize < 1000000) { // Limit file size to 1MB
                $imgData = file_get_contents($fileTmpName);
                $imgType = $fileType;
            } else {
                $response['message'] = 'Your file is too big!';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'You cannot upload files of this type!';
            echo json_encode($response);
            exit;
        }
    }

    // Prepare and execute the SQL query to insert product details
    $query = "INSERT INTO products (product_name, product_type, cost_per_unit, brand, type, ton, arm_length, bucket_size, dimension, fuel_tank_capacity, weight, capacity_size, compatible_excavator, usage_conditions, img, img_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'ssisssssssssssss',
        $product_name,
        $product_type,
        $cost_per_unit,
        $brand,
        $type,
        $ton,
        $arm_length,
        $bucket_size,
        $dimension,
        $fuel_tank_capacity,
        $weight,
        $capacity_size,
        $compatible_excavator,
        $usage_conditions,
        $imgData,
        $imgType
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Product added successfully';
    } else {
        $response['message'] = 'Failed to add product: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);

?>
