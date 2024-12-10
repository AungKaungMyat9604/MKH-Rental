<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['fullname'])) {
    header("Location: signin.php");
    exit();
}
$fullnameParts = explode(" ", $_SESSION['fullname']);
$firstName = $fullnameParts[0];
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'admin/adminHeader.php'; ?>
    <title>Rent Management</title>
</head>
<body>
    <?php include 'admin/adminNavbar.php'; ?>
    <section class="main1">
        <div class="wrapper">
            <?php include 'admin/adminSidebar.php'; ?>
            <div class="main" id="main">
                <main class="content px-1 py-4">
                    <div class="container-fluid">
                        <h2 class="fw-bold fs-1">Rent Management</h2>
                        <section id="displayFilterResult" class="container">
                            <div id="allhistorysdisplay"></div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </section>
    <?php include 'admin/adminScripts.php'; ?>
    <script>
        $(document).ready(function() {
            rentmanagement(); // Call your rent management JavaScript function
        });
    </script>
</body>
</html>
