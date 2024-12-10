<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    $user_id = $_SESSION['user_id'];
    $fullnameParts = explode(" ", $_SESSION['fullname']);
    $firstName = $fullnameParts[0];
} else {
    $user_id = '';
}
if ($_SESSION['role'] === 'admin') {
?>
    <!DOCTYPE html>
    <html lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <head>
    <title>Rent Management</title>
        <?php 
        include 'admin_header.php';
        include 'admin_style.php'; ?>
    </head>
    <body>
        <?php
        include 'admin_navbar.php'; ?>
    <section class="main1">
    <div id="loadingScreen" class="loading-screen">
        <div class="spinner"></div>
    </div>
    <div class="wrapper">
                <aside id="sidebar" class="">
                    <div class="d-flex">
                        <button class="toggle-btn" type="button">
                            <i class="lni lni-grid-alt"></i>
                        </button>
                        <div class="sidebar-logo">
                            <a href="#">Options</a>
                        </div>
                    </div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a href="admin_dashboard.php" class="sidebar-link filter-option" data-page="admin_dashboard">
                                <img src="images/dashboard.png" width="30" height="30">
                                <span class="mx-2">Main Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="admin_rentManagement.php" class="sidebar-link filter-option" data-page="admin_rentManagement">
                                <img src="images/insurance.png" width="30" height="30">
                                <span class="mx-2">Rent Management</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="admin_productManagement.php" class="sidebar-link filter-option" data-page="admin_productManagement">
                                <img src="images/new-product.png" width="30" height="30">
                                <span class="mx-2">Product Management</span>
                            </a>
                        </li>
                    </ul>
                </aside>
                <div class="main" id="main">
                    <main class="content px-1 py-4" style="background-color: rgb(179, 185, 189); height:100%;">
                        <div class="container-fluid" style="height:100%;">
                            <div class="container" id="searchFormContainer">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h2 class="fw-bold fs-1" id="header"></h2>
                                    </div>
                                </div>
                            </div>
                            <section id="displayFilterResult" class="container" style="height:100%;">
                                <section id="allhistorys" class="container-lg" style="padding: 10px; height:100%;">
                                    <div class="mt-1" id="addProductButton"></div>
                                    <div class="d-block d-md-block d-lg-block" style="height:100%;">
                                        <div class="row" id="allhistorysdisplay" style="height:75%;"></div>
                                    </div>
                                </section>
                            </section>
                        </div>
                    </main>
                </div>
            </div>
        </section>
        <?php 
        include 'admin_modals.php';
        include 'admin_script.php' ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const currentPage = 'admin_rentManagement'; // Current page identifier
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                if (link.getAttribute('data-page') === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });

        $(document).ready(function() {
            rentmanagement();
        });
        </script>
    </body>
    </html>

    <?php
} else {
    echo '<script type="text/javascript">
            alert("You cannot access this page. You will be redirected to the previous page.");
            window.history.back();
          </script>';
    exit();
}