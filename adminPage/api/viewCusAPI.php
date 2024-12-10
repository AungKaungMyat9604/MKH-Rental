<?php
session_start();
require 'dbinfo.php'; // include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    $query = "SELECT * FROM users WHERE user_id = ? AND is_deleted = 'no'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc(); // Fetch a single row
        echo json_encode([
            'success' => true,
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'township' => $data['township']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found with that ID.']);
    }

    $stmt->close();
    $conn->close();
}

?>
