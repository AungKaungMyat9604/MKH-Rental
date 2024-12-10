<?php
session_start();
require 'dbinfo.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $product_type = isset($_POST['product_type']) ? trim($_POST['product_type']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $cost_per_unit = isset($_POST['cost_per_unit']) ? floatval($_POST['cost_per_unit']) : 0;
    $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
    $ton = isset($_POST['ton']) ? floatval($_POST['ton']) : null;
    $arm_length = isset($_POST['arm_length']) ? floatval($_POST['arm_length']) : null;
    $bucket_size = isset($_POST['bucket_size']) ? floatval($_POST['bucket_size']) : null;
    $dimension = isset($_POST['dimension']) ? trim($_POST['dimension']) : null;
    $fuel_tank_capacity = isset($_POST['fuel_tank_capacity']) ? floatval($_POST['fuel_tank_capacity']) : null;
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : null;
    $capacity_size = isset($_POST['capacity_size']) ? trim($_POST['capacity_size']) : null;
    $compatible_excavator = isset($_POST['compatible_excavator']) ? trim($_POST['compatible_excavator']) : null;
    $usage_conditions = isset($_POST['usage_conditions']) ? trim($_POST['usage_conditions']) : '';

    if ($product_id > 0) {
        $query = "UPDATE products 
                  SET product_name = ?, 
                      product_type = ?, 
                      type = ?, 
                      cost_per_unit = ?, 
                      brand = ?, 
                      ton = ?, 
                      arm_length = ?, 
                      bucket_size = ?, 
                      dimension = ?, 
                      fuel_tank_capacity = ?, 
                      weight = ?, 
                      capacity_size = ?, 
                      compatible_excavator = ?, 
                      usage_conditions = ? 
                  WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'sssissssssssssi',
            $product_name,
            $product_type,
            $type,
            $cost_per_unit,
            $brand,
            $ton,
            $arm_length,
            $bucket_size,
            $dimension,
            $fuel_tank_capacity,
            $weight,
            $capacity_size,
            $compatible_excavator,
            $usage_conditions,
            $product_id
        );

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
