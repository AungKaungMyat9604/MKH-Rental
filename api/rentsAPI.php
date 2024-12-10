<?php
session_start();
require 'dbinfo.php';
require '../vendor/autoload.php'; // Make sure to use the correct path to autoload.php
date_default_timezone_set('Asia/Yangon');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $rfullname = $_POST['rfullname'];
    $remail = $_POST['remail'];
    $rphone = $_POST['rphone'];
    $rcity = $_POST['rcity'];
    $rtownship = $_POST['rtownship'];
    $rent_address = $_POST['rent_address'];
    $location = json_decode($_POST['hiddenLocation'], true);
    $start_date = $_POST['start_date'];
    $renting_hours = $_POST['renting_hours'];
    $end_date = $_POST['end_date'];
    $available_date = $_POST['available_date'];
    $total_cost = $_POST['total_cost'];
    $need_transportation = $_POST['need_transportation'];
    $pickup_datetime = $_POST['pickup_datetime'] ?? null;
    $transportation = $need_transportation == 1 ? 'Needed' : 'Not Needed';
    $now = gmdate('Y-m-d H:i:s');

    if (empty($pickup_datetime)) {
        $pickup_datetime = null;
    }

    // First query
    $sql = "INSERT INTO rent_forms (user_id, product_id, rfullname, remail, rphone, rcity, rtownship, rent_address, latitude, longitude, start_date, renting_hours,  end_date, total_cost, need_transportation, pickup_datetime, posted_datetime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iissssssddsississ",
        $user_id,
        $product_id,
        $rfullname,
        $remail,
        $rphone,
        $rcity,
        $rtownship,
        $rent_address,
        $location['lat'],
        $location['lng'],
        $start_date,
        $renting_hours,
        $end_date,
        $total_cost,
        $need_transportation,
        $pickup_datetime,
        $now
    );

    if ($stmt->execute()) {
        // If the first query is successful, proceed to the next one
        $sql2 = "INSERT INTO products_status (product_id, start_date, renting_hours, end_date, available_datetime) 
                 VALUES (?, ?, ?, ?, ?)";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param(
            "isiss",
            $product_id,
            $start_date,
            $renting_hours,
            $end_date,
            $available_date
        );

        if ($stmt2->execute()) {

            // Update products_status.is_active
            $sql3 = "
                UPDATE products_status
                SET is_done = 1
                WHERE available_datetime <= NOW()
                AND is_done = 0
                AND (end_date IS NULL OR end_date < NOW())";
            $stmt3 = $conn->prepare($sql3);

            if ($stmt3->execute()) {
                // Update products.is_active based on the latest status
                $sql4 = "
                    UPDATE products p
                    JOIN (
                        SELECT product_id, MAX(is_done) AS active_status
                        FROM products_status
                        GROUP BY product_id
                    ) ps ON p.product_id = ps.product_id
                    SET p.is_active = ps.active_status";
                $stmt4 = $conn->prepare($sql4);

                if ($stmt4->execute()) {
                    // Fetch the product name
                    $sql5 = "SELECT product_name FROM products WHERE product_id = ?";
                    $stmt5 = $conn->prepare($sql5);
                    $stmt5->bind_param("i", $product_id);
                    echo "Success";
                
                    if ($stmt5->execute()) {
                        $result = $stmt5->get_result();
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $product_name = $row['product_name'];
                        } else {
                            echo "Failed to fetch product name.";
                            exit;
                        }
                    } else {
                        echo "Failed to execute stmt5: " . $stmt5->error;
                        exit;
                    }
                
                    // Email sending process
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->SMTPDebug = 2; // Enable full debug output
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'rentalbymkh@gmail.com';
                        $mail->Password = 'fpfn pgnd gttb yuli';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );
                
                        $mail->setFrom('rentalbymkh@gmail.com', 'MKH Rental');
                        $mail->addAddress($remail, $rfullname);
                
                        $mail->isHTML(true);
                        $mail->Subject = 'Rent Form Confirmation';
                        $mail->Body = "<p>Dear $rfullname,</p><p>Your rent request for $product_name has been successfully submitted. The total renting hour is $renting_hours Hrs and the total cost is $total_cost Kyats.</p><p>Thank you!</p>";
                
                        $mail->send();
                
                        $mail->clearAddresses();
                        $adminemail = 'aungkaungmyat9604@gmail.com';
                        $adminname = 'Admin';
                        $mail->addAddress($adminemail, $adminname);
                
                        $mail->isHTML(true);
                        $mail->Subject = 'New Rent Form Uploaded!';
                        $mail->Body = "<p>Dear $adminname,</p>
                                        <p>A Customer uploaded a rent form.</p>
                                        <p>Product Name: $product_name</p>
                                        <p>Customer Name: $rfullname</p>
                                        <p>Customer Email: $remail</p>
                                        <p>Customer Phone: $rphone</p>
                                        <p>Rent Address: $rent_address</p>
                                        <p>Start Date: $start_date</p>
                                        <p>Renting Hours: $renting_hours Hrs</p>
                                        <p>Transportation: $transportation</p>
                                        <p><a href=\"https://www.google.com/maps?q=" . $location['lat'] . "," . $location['lng'] . "\">View Location in Google Map</a></p>
                                        <p>Thank you!</p>";
                
                        $mail->send();

                    } catch (Exception $e) {
                        echo "Success: Rent form submitted, but the confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Failed to update products.is_active: " . $stmt4->error;

                }
            } else {
                echo "Failed to update products_status.is_active: " . $stmt3->error;
            }
        } else {
            echo "Failed to execute stmt2: " . $stmt2->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statements
    $stmt->close();
    $stmt2->close();
    $stmt3->close();
    $stmt4->close();
    // Close connection
    $conn->close();
}
