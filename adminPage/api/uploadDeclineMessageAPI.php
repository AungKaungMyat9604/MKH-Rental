<?php
session_start();
require 'dbinfo.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $form_id = $_POST['form_id'];
    $customer_email = $_POST['remail'];
    $customer_name = $_POST['rfullname'];
    $message_why = $_POST['message_why'];

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
        $query2 = "UPDATE rent_forms SET rent_status = 'declined', is_canceled = 1, message_why = ? WHERE rent_forms_id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param('si', $message_why, $form_id);
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

        // Send email notification to the customer
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rentalbymkh@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'fpfn pgnd gttb yuli';    // Replace with your app password
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
            $mail->addAddress($customer_email, $customer_name);
            
            $mail->isHTML(true);
            $mail->Subject = 'Your Rent Request Has Been Declined';
            $mail->Body    = "<p>Dear $customer_name,</p><p>We regret to inform you that your rent request for the product <strong>$product_name</strong> has been declined.</p><p>Reason: $message_why</p><p>Thank You!</p>";
            
            $mail->send();
            echo 'Success';
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo 'Error: Rent form declined but email could not be sent.';
        }

    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log('Failed to decline the rent form: ' . $e->getMessage());
        echo 'Failed to decline the rent form.';
    }

    $conn->close();
}
?>
