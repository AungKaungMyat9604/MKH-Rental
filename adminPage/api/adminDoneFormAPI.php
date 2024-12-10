<?php
session_start();
require 'dbinfo.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the user is an admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $rent_forms_id = $_POST['form_id'];
    $customer_email = $_POST['remail'];
    $customer_name = $_POST['rfullname'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update rent_forms to mark as approved
        $query1 = "UPDATE rent_forms SET rent_status = 'finished' WHERE rent_forms_id = ?";
        $stmt1 = $conn->prepare($query1);
        if (!$stmt1) {
            throw new Exception($conn->error);
        }
        $stmt1->bind_param('i', $rent_forms_id);
        $stmt1->execute();
        $stmt1->close();

        // Update products to set it as inactive (unavailable)
        $query2 = "UPDATE products SET is_active = 1 WHERE product_id = ?";
        $stmt2 = $conn->prepare($query2);
        if (!$stmt2) {
            throw new Exception($conn->error);
        }
        $stmt2->bind_param('i', $product_id);
        if($stmt2->execute()){
            echo 'Success';
        }
        $stmt2->close();

        $query3 = "UPDATE products_status SET is_done = 1 WHERE product_id = ?";
        $stmt3 = $conn->prepare($query3);
        if (!$stmt3) {
            throw new Exception($conn->error);
        }
        $stmt3->bind_param('i', $product_id);
        if($stmt3->execute()){
            echo 'Success';
        }
        $stmt3->close();

        // Commit the transaction
        $conn->commit();

        // Send approval email to the customer
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
            $mail->addAddress($customer_email, $customer_name);
            
            $mail->isHTML(true);
            $mail->Subject = 'Your Rent Process Has Been Finished';
            $mail->Body    = "<p>Dear $customer_name,</p><p>Your rent process with Product Name: $product_name has been finished.</p><p>Thank you for choosing us.</p><p>Hoping to come back next time :)</p>";
            
            if ($mail->send()) {
                echo 'Success';
            } else {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo 'Error: Rent form finished but email could not be sent.';
        }
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log('Failed to set the rent form to Finished: ' . $e->getMessage());
        echo 'Failed to set the rent form to Finished.';
    }

    $conn->close();
} else {
    echo 'Unauthorized access.';
}
?>
