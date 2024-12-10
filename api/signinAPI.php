<?php
session_start();
require 'dbinfo.php'; // include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($email) || empty($password)) {
        echo 'All fields are required.';
        exit;
    }

    $query = "SELECT * FROM users WHERE email = ? AND is_deleted = 'no'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['city'] = $user['city'];
            $_SESSION['township'] = $user['township']; // storing fullname in session

            if ($user['role'] == 'admin') {
                echo 'admin';
            } else {
                echo 'customer';
            }
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo 'No user found with that email.';
    }

    $stmt->close();
    $conn->close();
}
?>
