<?php
session_start();
require 'dbinfo.php';

$user_id = $_GET['user_id'];

$sql = "SELECT profile_pic, profile_pic_type FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imgData = $row['profile_pic'];
    $imgType = $row['profile_pic_type'];

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
