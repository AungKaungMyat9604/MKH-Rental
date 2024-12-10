<?php
session_start();

$oldPassword = htmlspecialchars($_POST['oldPassword']);
$newPassword = htmlspecialchars($_POST['newPassword']);

require 'dbinfo.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$query1 = "SELECT password FROM users WHERE email=?";
$stmt1 = $conn->prepare($query1);

if ($stmt1) {
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $stmt1->store_result();

    if ($stmt1->num_rows == 1) {
        $stmt1->bind_result($currentPassword);
        $stmt1->fetch();

        if (password_verify($oldPassword, $currentPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $query2 = "UPDATE users SET password=? WHERE email=?";
            $stmt2 = $conn->prepare($query2);

            if ($stmt2) {
                $stmt2->bind_param("ss", $hashedPassword, $email);
                $stmt2->execute();

                if ($stmt2->affected_rows > 0) {
                    echo 'Success';
                } else {
                    echo 'Error updating password';
                }

                $stmt2->close();
            } else {
                echo 'Error in prepared statement';
            }
        } else {
            echo 'Invalid old password';
        }

        $stmt1->close();
    } else {
        echo 'User not found';
    }
} else {
    echo 'Error in prepared statement';
}

$conn->close();
?>
