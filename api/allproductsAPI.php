<?php
session_start();
require 'dbinfo.php';


$query = 'SELECT product_name,
            product_type,
            type,
            cost_per_unit,
            brand,
            ton,
            arm_length,
            bucket_size,
            dimension,
            fuel_tank_capacity,
            weight,
            capacity_size,
            compatible_excavator,
            usage_conditions,
            is_active,
            rating,
            product_id FROM products ORDER BY product_name ASC';
$result = $conn->query($query);
$data = [];
while ($row = $result->fetch_assoc()) {
    // Add the recipe_id to the data
    $row['product_id'] = (int)$row['product_id'];
    $row['is_active'] = (bool)$row['is_active'];
    $data[] = $row;
}
echo json_encode($data);
?>
