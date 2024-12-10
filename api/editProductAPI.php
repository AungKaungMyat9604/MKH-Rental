<?php
session_start();
require 'dbinfo.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $cost_per_unit = isset($_POST['cost_per_unit']) ? $_POST['cost_per_unit'] : 0;
    $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $ton = isset($_POST['ton']) ? $_POST['ton'] : null;
    $arm_length = isset($_POST['arm_length']) ? $_POST['arm_length'] : null;
    $bucket_size = isset($_POST['bucket_size']) ? $_POST['bucket_size'] : null;
    $dimension = isset($_POST['dimension']) ? $_POST['dimension'] : null;
    $fuel_tank_capacity = isset($_POST['fuel_tank_capacity']) ? $_POST['fuel_tank_capacity'] : null;
    $weight = isset($_POST['weight']) ? $_POST['weight'] : null;
    $capacity_size = isset($_POST['capacity_size']) ? $_POST['capacity_size'] : null;
    $compatible_excavator = isset($_POST['compatible_excavator']) ? $_POST['compatible_excavator'] : null;
    $usage_conditions = isset($_POST['usage_conditions']) ? $_POST['usage_conditions'] : '';
    $is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : 0;

    // Handle image upload
    $img = null;
    $img_type = null;

    if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
        $img = file_get_contents($_FILES['img']['tmp_name']);
        $img_type = $_FILES['img']['type'];
    }

    if ($product_id > 0) {
        $query = "UPDATE products SET 
                    product_name = ?, 
                    product_type = ?, 
                    type = ?,
                    cost_per_unit = ?, 
                    brand = ?, 
                    type = ?, 
                    ton = ?, 
                    arm_length = ?, 
                    bucket_size = ?, 
                    dimension = ?, 
                    fuel_tank_capacity = ?, 
                    weight = ?, 
                    capacity_size = ?, 
                    compatible_excavator = ?, 
                    usage_conditions = ?, 
                    is_active = ?, 
                    updated_at = NOW()";

        // Append image fields if an image is uploaded
        if ($img !== null && $img_type !== null) {
            $query .= ", img = ?, img_type = ?";
        }

        $query .= " WHERE product_id = ?";

        $stmt = $conn->prepare($query);

        if ($img !== null && $img_type !== null) {
            $stmt->bind_param(
                'ssssissssssssssissi',
                $product_name,
                $product_type,
                $type,
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
                $is_active,
                $img,
                $img_type,
                $product_id
            );
        } else {
            $stmt->bind_param(
                'ssssissssssssssii',
                $product_name,
                $product_type,
                $type,
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
                $is_active,
                $product_id
            );
        }

        if ($stmt->execute()) {
            echo 'Success';
        } else {
            echo 'Failed to update product details';
        }

        $stmt->close();
    } else {
        echo 'Invalid product ID';
    }

    $conn->close();
}
?>
