<?php
session_start();
require 'dbinfo.php';
    // Send approval email to the customer
        require 'vendor/autoload.php';
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rentalservicebymkh@gmail.com';  // Use environment variable or config file
            $mail->Password = 'MKHRental@1234';  // Use environment variable or config file
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('rentalservicebymkh@gmail.com', 'Admin');
            $mail->addAddress($customer_email, $customer_name);
            
            $mail->isHTML(true);
            $mail->Subject = 'Your Rent Request Has Been Approved';
            $mail->Body    = "<p>Dear $customer_name,</p><p>Your rent request for Product ID $product_id has been approved.</p><p>Thank you!</p>";
            
            $mail->send();
            echo 'Success: Rent form approved and email sent.';
            if(!$email->send())
{
     echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}        } catch (Exception $e) {
            // Log the error instead of echoing
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo 'Error: Rent form approved but email could not be sent.';
        }

     catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        // Log the error instead of echoing
        error_log('Failed to approve the rent form: ' . $e->getMessage());
        echo 'Failed to approve the rent form.';
    }

?>
