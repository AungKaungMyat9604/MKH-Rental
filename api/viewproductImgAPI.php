<?php
session_start();
require 'dbinfo.php';

$product_id = $_GET['product_id'];

$sql = "SELECT img, img_type FROM products WHERE product_id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imgData = $row['img'];
    $imgType = $row['img_type'];

    if ($imgData !== null && $imgType !== null) {
        header("Content-Type: " . $imgType);
        echo $imgData;
    } else {
        // Output default image if profile_pic or profile_pic_type is null
        $defaultImagePath = $_SERVER['DOCUMENT_ROOT'] . '/Final Project (Excavator Renting Website)/images/edit_profile.png';
        if (file_exists($defaultImagePath)) {
            echo file_get_contents($defaultImagePath);
        } else {
            // Default image not found error handling
            echo "Default image not found.";
        }
    }
    
} else {
    // Output default image if no user found
    $defaultImagePath = $_SERVER['DOCUMENT_ROOT'] . '/Final Project (Excavator Renting Website)/images/unknown_pfp.jpg';
    if (file_exists($defaultImagePath)) {
        echo file_get_contents($defaultImagePath);
    } else {
        // Default image not found error handling
        echo "Default image not found.";
    }
}

$conn->close();
?>
