<?php
session_start();
require 'dbinfo.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT rf.*,
                p.product_name,
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
            p.product_id FROM rent_forms AS rf JOIN products AS p ON rf.product_id = p.product_id ORDER BY rf.rent_forms_id DESC");

    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    $data = [];

    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $row['product_id'] = (int)$row['product_id'];
        $row['is_active'] = (bool)$row['is_active'];
        $data[] = $row;
    }

    // Close the statement
    $stmt->close();

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($data);

} else {
    // Return an empty JSON array if the user is not logged in
    $data = [];
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Close the database connection
$conn->close();

?>


