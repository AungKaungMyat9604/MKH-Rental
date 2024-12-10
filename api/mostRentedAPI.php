<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();
    require 'dbinfo.php';

    // Base query
    $query = "SELECT 
    p.product_id,
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
    p.rating,
    COUNT(rf.product_id) AS rent_count
FROM products AS p
LEFT JOIN rent_forms AS rf ON p.product_id = rf.product_id
WHERE p.is_active = 1
GROUP BY 
    p.product_id, 
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
    p.rating
ORDER BY rent_count DESC
LIMIT 4";



    // Prepare the statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    $searchResults = [];
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Get the saved products for the user
        $savedQuery = "SELECT product_id FROM saved_products WHERE user_id = ?";
        $savedStmt = $conn->prepare($savedQuery);
        if ($savedStmt) {
            $savedStmt->bind_param("i", $user_id);
            $savedStmt->execute();
            $savedResult = $savedStmt->get_result();
            $savedProducts = [];

            while ($savedRow = $savedResult->fetch_assoc()) {
                $savedProducts[] = $savedRow['product_id'];
            }

            // Mark the saved products in the search results
            foreach ($searchResults as &$product) {
                $product['is_saved'] = in_array($product['product_id'], $savedProducts);
            }

            $savedStmt->close();
        } else {
            error_log('Error in preparing statement: ' . $conn->error);
        }
    } else {
        // If user is not logged in, mark all products as not saved
        foreach ($searchResults as &$product) {
            $product['is_saved'] = false;
        }
    }

    // Close the statement
    $stmt->close();

    // Return the search results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit;
}
