<?php
require 'dbinfo.php';
require '../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $rent_form_id = $_POST['rent_form_id'];
    $additional_hours = intval($_POST['additional_hours']);

    // Fetch current end date, renting hours, and cost_per_duty from the database


    $sql = "SELECT rf.*, p.* FROM rent_forms rf
            JOIN products AS p ON rf.product_id = p.product_id
            WHERE rf.rent_forms_id = ? AND rf.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $rent_form_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        $end_date = new DateTime($data['end_date']);
        $current_renting_hours = intval($data['renting_hours']);
        $cost_per_duty = floatval($data['cost_per_unit']);
        $product_name = $data['product_name'];
        $rfullname = $data['rfullname'];
        $remail = $data['remail'];
        $rphone = $data['rphone'];
        $rent_address = $data['rent_address'];
        $start_date = $data['start_date'];
    } else {
        echo json_encode(['error' => 'Invalid product ID or rental not found.']);
        exit;
    }

    // Add additional hours to the current renting hours
    $new_renting_hours = $current_renting_hours + $additional_hours;
    $hours_left = $additional_hours;

    // Loop through each day and account for working hours to calculate new end date
    while ($hours_left > 0) {
        $current_time = $end_date->format('H:i');
        if ($current_time >= '09:00' && $current_time < '17:00') {
            $end_of_day = clone $end_date;
            $end_of_day->setTime(17, 0);
            $hours_today = min($end_of_day->diff($end_date)->h, $hours_left);
            $end_date->modify("+$hours_today hours");
            $hours_left -= $hours_today;
        }

        if ($hours_left > 0) {
            $end_date->modify('+1 day');
            $end_date->setTime(9, 0);
        }
    }

    // Recalculate cost
    if ($new_renting_hours <= 8) {
        $new_cost = $cost_per_duty;
    } else {
        $new_cost = $cost_per_duty + (($new_renting_hours - 8) * ($cost_per_duty / 8));
    }

    // Calculate new available date
    $available_date = clone $end_date;
    $available_date->modify('+1 hour');

    // Format the DateTime objects to strings
    $new_end_date = $end_date->format('Y-m-d H:i:s');
    $available_date_formatted = $available_date->format('Y-m-d H:i:s');

    // Update the rent_forms table with the new renting hours and end date
    $update_sql = "UPDATE rent_forms SET renting_hours = ?, end_date = ?, total_cost = ? WHERE product_id = ? AND rent_forms_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("issii", $new_renting_hours, $new_end_date, $new_cost, $product_id, $rent_form_id);

    // Update the products_status table with the new renting hours, end date, and available date
    $update_sql2 = "UPDATE products_status SET renting_hours = ?, end_date = ?, available_datetime = ? WHERE product_id = ?";
    $update_stmt2 = $conn->prepare($update_sql2);
    $update_stmt2->bind_param("issi", $new_renting_hours, $new_end_date, $available_date_formatted, $product_id);

    if ($update_stmt->execute() && $update_stmt2->execute()) {
        echo 'Success';
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
            $mail->Subject = 'Rent Form Updated';
            $mail->Body    = "<p>Dear $rfullname,</p><p>Your rent request for $product_name has been updated. The new total renting hour is $new_renting_hours Hrs and the new total cost is $new_cost Kyats. The new end date is $new_end_date.(24Hrs)</p><p>Thank you!</p>";

            $mail->send();

            $mail->clearAddresses();
            $adminemail = 'aungkaungmyat9604@gmail.com';
            $adminname = 'Admin';
            $mail->addAddress($adminemail, $adminname);

            $mail->isHTML(true);
            $mail->Subject = 'Extension Rent Form Uploaded!';
            $mail->Body = "<p>Dear $adminname,</p>
                                        <p>A Customer uploaded an Extension rent form.</p>
                                        <p>Product Name: $product_name</P>
                                        <p>Customer Name: $rfullname</p>
                                        <p>Customer Email: $remail</p>
                                        <p>Customer Phone: $rphone</p>
                                        <p>Rent Address: $rent_address</p>
                                        <p>Start Date: $start_date</p>
                                        <p>Old Renting Hours: $current_renting_hours Hrs</p>
                                        <p>Added Hours: $additional_hours Hrs</p>
                                        <p>New Total Renting Hours: $new_renting_hours Hrs</p>
                                        <p>Thank you!</p>";

            $mail->send();
        } catch (Exception $e) {
            echo "Success: Rent form updated, but the confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo json_encode(['error' => 'Failed to update the rent form.']);
    }

    $update_stmt->close();
    $update_stmt2->close();
    $conn->close();
}
