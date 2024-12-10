<?php
session_start();
require 'dbinfo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($conn->connect_error) {
        die($conn->connect_error);
    }

    $email = $_SESSION['email'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if confirmPassword is provided
    if (empty($confirmPassword)) {
        echo 'Please enter your password to confirm.';
        exit;
    }

    // Retrieve the stored password hash from the database
    $query = "SELECT password FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($storedPasswordHash);
    $stmt->fetch();
    $stmt->close();

    // Verify the entered password against the stored password hash
    if (password_verify($confirmPassword, $storedPasswordHash)) {
        // Password is correct, proceed to delete the account
        $deleteQuery = "UPDATE users SET is_deleted='yes' WHERE email=?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('s', $email);
        
        if ($deleteStmt->execute()) {
            session_unset();
            session_destroy();
            echo 'Success';
        } else {
            echo 'Failed to delete profile';
        }

        $deleteStmt->close();
    } else {
        // Password does not match
        echo 'Incorrect password. Please try again.';
    }

    $conn->close();
} else {
    echo 'Invalid request method';
}
?>
