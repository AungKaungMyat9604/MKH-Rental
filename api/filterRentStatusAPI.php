<?php
session_start();
header('Content-Type: application/json');

require 'dbinfo.php';

// Fetch the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch filters from POST data
$filters = isset($_POST['filters']) ? $_POST['filters'] : [];

// Base SQL query
$sql = "SELECT 
            rf.*,
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
            p.product_id
        FROM 
            products p
        INNER JOIN 
            rent_forms rf 
        ON 
            p.product_id = rf.product_id
        WHERE 
            rf.user_id = ? 
        AND 
            rf.is_canceled = 0";

// Adding filters dynamically
$filterConditions = [];
$filterValues = [];

foreach ($filters as $key => $value) {
    $filterConditions[] = "$key = ?";
    $filterValues[] = $value;
}

if (!empty($filterConditions)) {
    $sql .= " AND " . implode(" AND ", $filterConditions);
}

$sql .= " ORDER BY rf.rent_forms_id DESC";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Prepare types for binding (assuming all filters are strings, adjust as needed)
$types = str_repeat('s', count($filterValues) + 1); // 's' for each filter and user_id

// Merge $user_id and $filterValues for binding
$params = array_merge([$user_id], $filterValues);

// Bind parameters
$stmt->bind_param($types, ...$params);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the data as JSON
echo json_encode($data);

?>
