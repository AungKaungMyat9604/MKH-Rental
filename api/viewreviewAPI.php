<?php
session_start();
require 'dbinfo.php';

if (isset($_POST['action']) && $_POST['action'] == 'filter_comments') {
  $product_id = $_POST['product_id'];
  $star = $_POST['star'];

  $stmt = $conn->prepare("SELECT pr.review_id, pr.user_id, u.fullname, pr.rating, pr.message, pr.datetime
                          FROM products_reviews AS pr
                          JOIN users AS u ON pr.user_id = u.user_id
                          WHERE pr.product_id = ? AND pr.rating = ?
                          ORDER BY pr.review_id DESC");
  $stmt->bind_param("ii", $product_id, $star);
  $stmt->execute();
  $result = $stmt->get_result();

  $ratingsList = array();
  while ($row = $result->fetch_assoc()) {
    $datetime = new DateTime($row['datetime'], new DateTimeZone('UTC'));
    $datetime->setTimezone(new DateTimeZone('Asia/Yangon'));
    $formattedDate = $datetime->format('l jS \of F Y h:i:s A');

    $ratingsList[] = array(
      'review_id' => $row['review_id'],
      'user_id' => $row['user_id'],
      'fullname' => $row['fullname'],
      'rating' => $row['rating'],
      'message' => $row['message'],
      'datetime' => $formattedDate
    );
  }

  $output = array('ratingsList' => $ratingsList);
  echo json_encode($output);

  $stmt->close();
  $conn->close();
}
?>