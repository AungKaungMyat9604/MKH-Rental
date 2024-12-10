<?php
session_start();
require 'dbinfo.php';

// Set default timezone
date_default_timezone_set('Asia/Yangon'); // Adjust your timezone

if (isset($_POST['rating'])) {
    $rating = $_POST['rating'];
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $message = $_POST['message'];

    // Get current UTC time in 'Y-m-d H:i:s' format
    $now = gmdate('Y-m-d H:i:s');

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO products_reviews (user_id, product_id, rating, message, datetime) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $product_id, $rating, $message, $now);

    if ($stmt->execute()) {
        echo "New Review Added Successfully";

        // Calculate the new average rating for the product
        $stmt_avg = $conn->prepare("SELECT AVG(rating) as avg_rating FROM products_reviews WHERE product_id = ?");
        $stmt_avg->bind_param("i", $product_id);
        $stmt_avg->execute();
        $result_avg = $stmt_avg->get_result();
        if ($row_avg = $result_avg->fetch_assoc()) {
            $avg_rating = $row_avg['avg_rating'];

            // Update the average rating in the products table
            $stmt_update = $conn->prepare("UPDATE products SET rating = ? WHERE product_id = ?");
            $stmt_update->bind_param("di", $avg_rating, $product_id);
            $stmt_update->execute();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['action']) && $_POST['action'] == 'load_data') {
    $product_id = $_POST['product_id'];

    $avgUserRatings = 0;
    $totalReviews = 0;
    $totalRatings5 = 0;
    $totalRatings4 = 0;
    $totalRatings3 = 0;
    $totalRatings2 = 0;
    $totalRatings1 = 0;
    $ratingsList = array();
    $totalRatings_avg = 0;

    $stmt = $conn->prepare("SELECT pr.review_id, pr.user_id, u.fullname, pr.rating, pr.message, pr.datetime
                             FROM products_reviews AS pr
                             JOIN users AS u ON pr.user_id = u.user_id
                             WHERE pr.product_id = ?
                             ORDER BY pr.review_id DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Convert datetime string to local datetime
        $datetime = new DateTime($row['datetime'], new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Asia/Yangon')); // Adjust as needed
        $formattedDate = $datetime->format('l jS \of F Y h:i:s A');

        $ratingsList[] = array(
            'review_id' => $row['review_id'],
            'user_id' => $row['user_id'],
            'fullname' => $row['fullname'],
            'rating' => $row['rating'],
            'message' => $row['message'],
            'datetime' => $formattedDate
        );
        switch ($row['rating']) {
            case '5': $totalRatings5++; break;
            case '4': $totalRatings4++; break;
            case '3': $totalRatings3++; break;
            case '2': $totalRatings2++; break;
            case '1': $totalRatings1++; break;
        }
        $totalReviews++;
        $totalRatings_avg += intval($row['rating']);
    }

    if ($totalReviews > 0) {
        $avgUserRatings = $totalRatings_avg / $totalReviews;
    }

    $output = array(
        'avgUserRatings' => number_format($avgUserRatings, 1),
        'totalReviews' => $totalReviews,
        'totalRatings5' => $totalRatings5,
        'totalRatings4' => $totalRatings4,
        'totalRatings3' => $totalRatings3,
        'totalRatings2' => $totalRatings2,
        'totalRatings1' => $totalRatings1,
        'ratingsList' => $ratingsList
    );

    echo json_encode($output);

    $stmt->close();
    $conn->close();
}
?>
