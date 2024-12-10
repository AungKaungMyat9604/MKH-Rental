<?php
// Start a session if needed
session_start();

// Include the database connection file
require 'dbinfo.php';

// Initialize the response array
$response = [];

// Check if the product_id is provided in the POST request
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Validate that the product_id is an integer
    if (filter_var($product_id, FILTER_VALIDATE_INT)) {
        
        // Use the original query to fetch the last rent form for the given product_id
        $query = "
            SELECT rf.*, u.fullname, u.user_id, u.township
            FROM rent_forms AS rf
            JOIN users AS u ON u.user_id = rf.user_id
            WHERE rf.product_id = ? AND rf.rent_forms_id = (
                SELECT MAX(rf2.rent_forms_id) 
                FROM rent_forms AS rf2 
                WHERE rf2.product_id = rf.product_id
            )
            LIMIT 1
        ";

        // Prepare the SQL statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the product_id parameter to the SQL query
            $stmt->bind_param("i", $product_id);

            // Execute the SQL statement
            $stmt->execute();

            // Get the result of the query
            $result = $stmt->get_result();

            // Check if there is at least one row in the result
            if ($result->num_rows > 0) {
                // Fetch the data as an associative array
                $response = $result->fetch_assoc();
            } else {
                // If no data is found, set an appropriate message
                $response['error'] = 'No rental information found for this product.';
            }

            // Close the statement
            $stmt->close();
        } else {
            // If the SQL statement preparation fails, set an error message
            $response['error'] = 'Failed to prepare the SQL statement.';
        }
    } else {
        // If the product_id is not valid, set an error message
        $response['error'] = 'Invalid product ID.';
    }
} else {
    // If the product_id is not provided, set an error message
    $response['error'] = 'Product ID not provided.';
}

// Set the content type to JSON and echo the response
header('Content-Type: application/json');
echo json_encode($response);
?>
