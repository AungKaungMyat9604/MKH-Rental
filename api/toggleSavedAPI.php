<?php
session_start();
require 'dbinfo.php'; 

$response = array("success" => false, "message" => "", "isSaved" => false);

if (isset($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];


    $checkQuery = "SELECT * FROM saved_products WHERE user_id = $user_id AND product_id = $product_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {

        $removeQuery = "DELETE FROM saved_products WHERE user_id = $user_id AND product_id = $product_id";
        $removeResult = mysqli_query($conn, $removeQuery);

        if ($removeResult) {
            $response["success"] = true;
            $response["message"] = "Product removed from Saved List successfully.";
            $response["isSaved"] = false;
        } else {
            $response["message"] = "Failed to remove product from Saved. Please try again later.";
        }
    } else {

        $insertQuery = "INSERT INTO saved_products (user_id, product_id) VALUES ($user_id, $product_id)";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $response["success"] = true;
            $response["message"] = "Product added to Saved List successfully.";
            $response["isSaved"] = true;
        } else {
            $response["message"] = "Failed to add product to Saved. Please try again later.";
        }
    }
} else {
    $response["message"] = "User not logged in or missing parameters.";
}

header("Content-Type: application/json");
echo json_encode($response);
?>
