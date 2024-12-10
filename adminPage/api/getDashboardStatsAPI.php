
<?php
session_start();
require 'dbinfo.php';

$response = ['success' => false, 'message' => '', 'data' => []];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "
    SELECT 
        (SELECT COUNT(*) FROM rent_forms WHERE rent_status = 'finished' OR rent_status = 'approved / ongoing') AS total_rents,
        (SELECT COUNT(*) FROM users WHERE role = 'customer') AS total_customers,
        (SELECT COUNT(*) FROM products) AS total_products,
        (SELECT SUM(total_cost) FROM rent_forms WHERE rent_status = 'finished') AS total_cost
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $response['success'] = true;
    $response['data'] = $result->fetch_assoc();
} else {
    $response['message'] = "Failed to fetch data.";
}

$conn->close();
echo json_encode($response);
?>