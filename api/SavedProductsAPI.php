<?php
session_start();
 
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
 
    require 'dbinfo.php';

 
    $stmt = $conn->prepare("
        SELECT p.product_name,
            p.product_type,
            p.type,
            p.cost_per_unit,
            p.brand,
            p.ton,
            p.arm_length,
            p.bucket_size,
            p.dimension,
            p.fuel_tank_capacity,
            p.weight,
            p.capacity_size,
            p.compatible_excavator,
            p.usage_conditions,
            p.is_active,
            p.product_id
        FROM saved_products AS sp
        JOIN products AS p ON sp.product_id = p.product_id
        WHERE sp.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
 
    $result = $stmt->get_result();
    $savedProducts = [];
    $savedProducts = [];
    while ($row = $result->fetch_assoc()) {
        $row['product_id'] = (int)$row['product_id'];
        $row['is_active'] = (bool)$row['is_active'];
        $savedProducts[] = $row;
    }
 
    header('Content-Type: application/json');
    echo json_encode($savedProducts);
} else {
    $savedProducts = [];
    header('Content-Type: application/json');
    echo json_encode($savedProducts);
}
?>