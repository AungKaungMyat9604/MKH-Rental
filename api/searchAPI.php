<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchQuery = '%' . $_POST['search'] . '%';
    $filters = isset($_POST['filters']) ? $_POST['filters'] : [];

    session_start();
    require 'dbinfo.php';

    // Base query
    $query = "SELECT product_name,
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
            product_id FROM products WHERE 
        (product_name LIKE ? OR 
        product_type LIKE ? OR 
        brand LIKE ? OR
        type LIKE ? OR
        usage_conditions LIKE ? OR
        ton LIKE ?)";

    // Add filter conditions if filters are provided
    $filterConditions = [];
    $filterValues = [];
    foreach ($filters as $key => $value) {
        if ($key == 'usage_conditions') {
            $filterConditions[] = "usage_conditions LIKE ?";
            $filterValues[] = '%' . $value . '%';  // Use % for LIKE clause
        } else {
            $filterConditions[] = "$key = ?";
            $filterValues[] = $value;
        }
    }
    if (!empty($filterConditions)) {
        $query .= " AND " . implode(' AND ', $filterConditions);
    }

    // Prepare the statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind parameters
    $types = str_repeat('s', 6 + count($filterValues));
    $params = array_merge([$searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery], $filterValues);
    $stmt->bind_param($types, ...$params);

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
        } else {
            error_log('Error in preparing statement: ' . $conn->error);
        }
    } else {
        // If user is not logged in, mark all products as not saved
        foreach ($searchResults as &$product) {
            $product['is_saved'] = false;
        }
    }

    // Return the search results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit;
}
?>
