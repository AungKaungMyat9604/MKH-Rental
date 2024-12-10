<?php
session_start();
require 'dbinfo.php';

$response = ['success' => false, 'message' => ''];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['file']) && isset($_POST['product_id'])) {
    $file = $_FILES['file'];

    // File properties
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Get product ID from POST data
    $product_id = intval($_POST['product_id']);

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) { // limit file size to 1MB
                $imgData = file_get_contents($fileTmpName); // Get image data

                // Use a prepared statement to prevent SQL injection
                $query = "UPDATE products SET img = ?, img_type = ? WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('bsi', $imgData, $fileType, $product_id);

                // Send image data as blob
                $stmt->send_long_data(0, $imgData);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Product image updated successfully!';
                    $response['profilePicSrc'] = 'api/viewproductImgAPI.php?product_id=' . urlencode($product_id); // Return URL to fetch the image
                } else {
                    $response['message'] = 'Error updating product image: ' . $stmt->error;
                }

                $stmt->close();
            } else {
                $response['message'] = 'Your file is too big!';
            }
        } else {
            $response['message'] = 'There was an error uploading your file!';
        }
    } else {
        $response['message'] = 'You cannot upload files of this type!';
    }
} else {
    $response['message'] = 'No file or product ID provided.';
}

$conn->close();
echo json_encode($response);
?>
