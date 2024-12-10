<?php
session_start();

require 'dbinfo.php';

$response = ['success' => false, 'message' => ''];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // File properties
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    
    // Get user email from session (assuming it's properly set in the session)
    $user_id = $_SESSION['user_id'];

    // Get file extension
    $fileParts = explode('.', $fileName);
    $fileExt = strtolower(end($fileParts));

    // Allowed file types
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) { // limit file size to 1MB
                $imgData = addslashes(file_get_contents($fileTmpName));

                // Check if user already has a profile picture in the database
                $checkSql = "SELECT * FROM users WHERE user_id = '$user_id'";
                $result = $conn->query($checkSql);

                if ($result->num_rows > 0) {
                    // Update existing record
                    $updateSql = "UPDATE users SET profile_pic = '$imgData', profile_pic_type = '$fileType' WHERE user_id = '$user_id'";

                    if ($conn->query($updateSql) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = 'Profile picture updated successfully!';
                        $response['profilePicSrc'] = 'api/viewprofilepictureAPI.php?user_id=' . urlencode($user_id); // Return URL to fetch the image
                    } else {
                        $response['message'] = 'Error updating profile picture: ' . $conn->error;
                    }
                } else {
                    $response['message'] = 'User not found or does not have an existing profile picture.';
                }
            } else {
                $response['message'] = 'Your file is too big!';
            }
        } else {
            $response['message'] = 'There was an error uploading your file!';
        }
    } else {
        $response['message'] = 'You cannot upload files of this type!';
    }
}

$conn->close();
echo json_encode($response);

?>
