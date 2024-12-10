<?php
session_start();
require 'dbinfo.php';

$fullname = htmlspecialchars($_POST['fullname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$city = htmlspecialchars($_POST['city']);
$township = htmlspecialchars($_POST['township']);

$query = "UPDATE users SET fullname=?, phone=?, city=?, township=? WHERE email=?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param('sssss', $fullname, $phone, $city, $township, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $query_fetch = "SELECT * FROM users WHERE email=?";
        $stmt_fetch = $conn->prepare($query_fetch);
        if ($stmt_fetch) {
            $stmt_fetch->bind_param('s', $email);
            $stmt_fetch->execute();
            $result_fetch = $stmt_fetch->get_result();

            if ($result_fetch->num_rows > 0) {
                $row = $result_fetch->fetch_assoc();

                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['city'] = $row['city'];
                $_SESSION['township'] = $row['township'];
                $_SESSION['email'] = $row['email'];

                echo 'Success';
            } else {
                echo 'Failed to fetch updated user information';
            }

            $stmt_fetch->close();
        } else {
            echo 'Failed to prepare fetch statement';
        }
    } else {
        echo 'No changes made or failed to update';
    }

    $stmt->close();
} else {
    echo 'Failed to prepare update statement';
}

$conn->close();
?>
