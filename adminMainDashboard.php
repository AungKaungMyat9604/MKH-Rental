<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['fullname'])) {
    header("Location: signin.php"); // Redirect to sign-in page if not logged in
    exit();
}
$fullnameParts = explode(" ", $_SESSION['fullname']);
$firstName = $fullnameParts[0];
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'admin/adminHeader.php'; // Include common header section ?>
    <title>Main Dashboard</title>
</head>
<body>
    <?php include 'admin/adminNavbar.php'; // Include common navbar ?>
    <section class="main1">
        <div class="wrapper">
            <?php include 'admin/adminSidebar.php'; // Include common sidebar ?>
            <div class="main" id="main">
                <main class="content px-1 py-4">
                    <div class="container-fluid">
                        <h2 class="fw-bold fs-1">Main Dashboard</h2>
                        <!-- Dashboard content goes here -->
                    </div>
                </main>
            </div>
        </div>
    </section>
    <?php include 'admin/adminScripts.php'; // Include common scripts ?>
</body>
</html>
