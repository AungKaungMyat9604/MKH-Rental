<?php
session_start();
require 'dbinfo.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT rf.*, p.*
    FROM rent_forms AS rf
    JOIN products AS p ON rf.product_id = p.product_id
    WHERE rf.user_id = ? AND rf.is_canceled = 0
    ORDER BY rf.rent_forms_id DESC");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['product_id'] = (int)$row['product_id'];
    $row['is_active'] = (bool)$row['is_active'];
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
} else {
$data = [];
header('Content-Type: application/json');
echo json_encode($data);
}
?>
