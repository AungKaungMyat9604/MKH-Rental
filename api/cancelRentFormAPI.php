<?php
session_start();
require 'dbinfo.php';
require '../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $rent_forms_id = $_POST['rent_forms_id'];

    // Fetch product details and customer information
    $sql = "SELECT p.product_name, rf.rfullname, rf.remail 
            FROM products p 
            JOIN rent_forms rf ON p.product_id = rf.product_id 
            WHERE rf.rent_forms_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $rent_forms_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        $product_name = $data['product_name'];
        $rfullname = $data['rfullname'];
        $remail = $data['remail'];
    } else {
        echo 'Failed to retrieve product or customer information';
        exit;
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete from products_status
        $query = "DELETE FROM products_status WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $stmt->close();

        // Update rent_forms
        $query2 = "UPDATE rent_forms SET is_canceled = 1 WHERE rent_forms_id = ? AND is_canceled = 0";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param('i', $rent_forms_id);
        $stmt2->execute();
        $stmt2->close();

        // Update products
        $query3 = "UPDATE products SET is_active = 1 WHERE product_id = ?";
        $stmt3 = $conn->prepare($query3);
        $stmt3->bind_param('i', $product_id);
        $stmt3->execute();
        $stmt3->close();

        // Commit the transaction
        $conn->commit();

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

            // Send email to the customer
            $mail->setFrom('rentalbymkh@gmail.com', 'MKH Rental');
            $mail->addAddress($remail, $rfullname);

            $mail->isHTML(true);
            $mail->Subject = 'Your Rent Form Cancellation';
            $mail->Body    = "<p>Dear $rfullname,</p><p>Your rent form for the product '$product_name' has been successfully canceled.</p><p>Thank you!</p>";
            $mail->send();

            // Send email to the admin
            $mail->clearAddresses();
            $adminemail = 'aungkaungmyat9604@gmail.com';
            $adminname = 'Admin';
            $mail->addAddress($adminemail, $adminname);

            $mail->isHTML(true);
            $mail->Subject = 'Rent Form Canceled';
            $mail->Body = "<p>Dear $adminname,</p>
                                        <p>The rent form for the product '$product_name' has been canceled by $rfullname.</p>
                                        <p>Thank you!</p>";
            $mail->send();

            
        } catch (Exception $e) {
            echo "Success: Rent form canceled, but the email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo 'Failed to Cancel';
    }

    $conn->close();
}
?>
