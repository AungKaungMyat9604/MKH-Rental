<?php
session_start();
require 'dbinfo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $password = htmlspecialchars($_POST['password']);
    $city = htmlspecialchars($_POST['city']);
    $township = htmlspecialchars($_POST['township']);

    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($city) || empty($township)) {
        echo 'All fields are required.';
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $checkEmailQuery = "SELECT * FROM users WHERE email = ? AND is_deleted = 'no'";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Already Registered';
        exit;
    } else {
        $insertQuery = "INSERT INTO users (fullname, email, phone, password, role, city, township) VALUES (?, ?, ?, ?, 'customer', ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('ssssss', $fullname, $email, $phone, $hashed_password, $city, $township);

        if ($stmt->execute()) {
            $query = "SELECT * FROM users WHERE email = ? AND is_deleted = 'no'";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result1 = $stmt->get_result();
            $row = $result1->fetch_assoc();

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['city'] = $row['city'];
            $_SESSION['township'] = $row['township'];

            echo 'Success';
        } else {
            echo 'Error: ' . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
