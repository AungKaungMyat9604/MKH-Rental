<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    $user_id = $_SESSION['user_id'];
    $fullnameParts = explode(" ", $_SESSION['fullname']);
    $firstName = $fullnameParts[0];
} else {
    $user_id = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<head>
    <title>MKH Rental_Products Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="icon" type="image/png" href="images/small_logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
    <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/products.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&libraries=places&callback=initMap"
        defer></script>
    <style>
.accessibility-circle {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 60px;
    height: 60px;
    background-color: #007bff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 1001;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease; /* Smooth transition */
}

.accessibility-circle:hover {
    transform: scale(1.1); /* Enlarge on hover */
}

/* Circle Menu */
.circle-menu {
    display: none; /* Hidden by default */
    position: fixed;
    bottom: 90px;
    left : 35px;
    z-index: 9999;
}

.circle-button {
    width: 50px;
    height: 50px;
    background-color: #007bff;
    border: none;
    color: white;
    font-size: 24px;
    border-radius: 50%;
    margin-bottom: 20px;
    cursor: pointer;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease; /* Smooth transition */
}

.circle-button:hover {
    background-color: #0056b3;
    transform: scale(1.1); /* Enlarge on hover */
}
    </style>
</head>

<body>
<div id="accessibility-icon" class="accessibility-circle">
    <i class="fas fa-universal-access"></i>
</div>

<div id="zoom-controls" class="circle-menu">
    <button id="zoom-in" class="circle-button"><i class="bi bi-zoom-in"></i></button>
    <button id="zoom-out" class="circle-button mx-2"><i class="bi bi-zoom-out"></i></button>
</div>
    <div id="loadingScreen" class="loading-screen">
        <div class="spinner"></div>
    </div>
    <!--NAVBAR-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index:999;">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="home.php">
                <img src="images/Logo.png" class="img-fluid" alt="MKH Group" style="width: auto; height: 55px; margin-left: 5px; border-radius: 15px;" />
            </a>

            <div class="d-flex align-items-center order-lg-2">
                <?php if (isset($_SESSION['email'])) { ?>
                    <li class="nav-item">
                        <a class="ms-5" href="savedList.php">
                            <i class="fa-solid fa-bookmark text-warning fs-3"></i>
                            <span class="badge bg-primary badge-number" style="margin-left:-8px;">0</span> <!-- The badge will be updated dynamically -->
                        </a>
                    </li>


                    <div class="nav-item dropdown d-flex align-items-center ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-md-block me-3 text-white">
                                <i class="fa-solid fa-caret-down p-1"></i><?php echo $firstName; ?>
                            </span>
                            <span class="d-block d-md-none me-2 text-white">
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                            <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Profile" class="rounded-circle img-fluid" style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
                        </a>
                        <li class="nav-item dropdown">
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
                                <li><a class="dropdown-item text-muted text-center"><?php echo $_SESSION['fullname']; ?></a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="profilesetting.php"><i class="fa-regular fa-address-card me-2"></i>Profile Settings</a></li>
                                <?php if (isset($_SESSION['email'])) { ?>
                                    <li><a href="savedList.php" class="dropdown-item" id="savedDropdown"><i class="fa-solid fa-bookmark me-2"></i> Saved List</a></li>
                                    <li><a class="dropdown-item" href="renthistory.php"><i class="fa-solid fa-clock-rotate-left me-2"></i></i>Rent History</a></li>
                                <?php } ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li><a class="dropdown-item" href="#" id="logout-link"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a></li>
                            </ul>
                    </div>
                <?php } else { ?>
                    <div class="nav-item dropdown d-flex align-items-center ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-md-block me-3 text-white">
                                <i class="fa-solid fa-caret-down p-1"></i>Log In
                            </span>
                            <span class="d-block d-md-none me-2 text-white">
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                            <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Profile" class="rounded-circle img-fluid" style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
                            <li><a class="dropdown-item" href="signin.php"><i class="fa-solid fa-right-to-bracket me-2"></i>Log In</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="signup.php"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>Sign Up</a></li>
                        </ul>
                    </div>
                <?php } ?>

                <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse order-lg-1" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About Us</a>
                    </li>
                </ul>


                <form class="d-flex d-none d-lg-block" id="searchForm" style="margin-top:auto; margin-bottom: auto;">
                    <div class="input-group mx-2">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput" />
                        <button id="searchproduct" class="btn btn-outline-secondary" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>


    <!--Main Content-->
    <section class="main1">
        <div class="wrapper">
            <aside id="sidebar" class="" style="z-index:998;">
                <div class="d-flex">
                    <button class="toggle-btn" type="button">
                        <i class="lni lni-grid-alt"></i>
                    </button>
                    <div class="sidebar-logo">
                        <a href="#">Categories</a>
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link filter-option" data-filter="" data-value="">
                            <img src="images/all.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">All Products</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#excavator" aria-expanded="false" aria-controls="excavator">
                            <img src="images/excavator1.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2" id="Excavator">Excavator</span>
                        </a>
                        <ul id="excavator" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="product_type" data-value="Excavator">All Excavators</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="brand" data-value="CAT" data-extra-filter="product_type" data-extra-value="Excavator">CAT Excavators</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="brand" data-value="Hitachi" data-extra-filter="product_type" data-extra-value="Excavator">Hitachi Excavators</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#excavatoratt" aria-expanded="false" aria-controls="excavatoratt">
                            <img src="images/driller.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Excavator Attachment</span>
                        </a>
                        <ul id="excavatoratt" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="product_type" data-value="Attachment">All Attachments</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="type" data-value="Breaker">Breaker Attachments</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="type" data-value="Bucket">Bucket Attachments</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link filter-option" data-filter="product_type" data-value="Truck">
                            <img src="images/truck.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Trucks</span>
                        </a>
                    </li> -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link filter-option" data-filter="is_active" data-value="1">
                            <img src="images/available.png" class="" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Available Excavators</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#usage_conditions" aria-expanded="false" aria-controls="usage_conditions">
                            <img src="images/usage_conditions.png" alt="usage_conditions Icon" width="30" height="30">
                            <span class="mx-2">Usage Conditions</span>
                        </a>
                        <ul id="usage_conditions" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="usage_conditions" data-value="Sandy" data-extra-filter="product_type" data-extra-value="Excavator">Sandy Condition<img src="images/sandy.png" class="mx-2" alt="Excavator Icon" width="30" height="30"></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="usage_conditions" data-value="Wet" data-extra-filter="product_type" data-extra-value="Excavator">Wet Condition<img src="images/wet.png" class="mx-2" alt="Excavator Icon" width="30" height="30"></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="usage_conditions" data-value="Muddy" data-extra-filter="product_type" data-extra-value="Excavator">Muddy Condition<img src="images/muddy.png" class="mx-2" alt="Excavator Icon" width="30" height="30"></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link filter-option" data-filter="usage_conditions" data-value="Rocky" data-extra-filter="product_type" data-extra-value="Excavator">Rocky Condition<img src="images/rocky.png" class="mx-2" alt="Excavator Icon" width="30" height="30"></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <div class="main" id="main">
                <main class="content px-1 py-4" style="background-color: rgb(179, 185, 189);">
                    <div class="container-fluid">
                        <nav aria-label="breadcrumb" class="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#">Products</a></li>
                                <li class="breadcrumb-item"><a href="#" class="currentbreadcrumb"></a></li>
                            </ol>
                        </nav>
                        <div class="container" id="searchFormContainer">
                            <form class="d-flex d-lg-none my-3" id="searchFormMobile">
                                <div class="input-group">
                                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInputMobile" />
                                    <button id="searchproductMobile" class="btn btn-outline-secondary" type="button">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="row align-items-center">
                                <h2 class="fw-bold fs-1">Products <span class="filter_header text-muted fw-normal fs-4"></span></h2>
                            </div>
                        </div>


                        <section id="displaySearchResults" class="container">
                            <section id="allproducts" class="container-lg" style="padding: 10px;">
                                <div class="d-block d-md-block d-lg-block">
                                    <div class="row" id="allproductsdisplay"></div>
                                </div>
                            </section>
                        </section>
                    </div>

                    <div class="modal fade" id="viewRentInfoModal" tabindex="-1" aria-labelledby="viewLocationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content px-md-4">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewRentInfoModalLabel">Where and When?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">City: <span id="city"></span></label>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label">Township: <span id="township"></span></label>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label">Start Date: <span id="start_date"></span></label>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label text-danger">End Date: <span id="end_date"></span></label>
                                    </div>
                                    <div id="adminMap" style="width: 100%; height: 220px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                                    <a href="#" id="openInGoogleMaps" class="btn btn-primary">View in Google Maps</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </main>
                <!-- Footer -->

                <footer class="text-center text-lg-start bg-dark text-muted">
                    <!-- Section: Social media -->
                    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom text-white">
                        <!-- Left -->
                        <div class="me-5 d-none d-lg-block">
                            <span>Get connected with us on social networks:</span>
                        </div>
                        <!-- Left -->

                        <!-- Right -->
                        <div>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="" class="me-4 text-reset">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>
                        <!-- Right -->
                    </section>
                    <!-- Section: Social media -->

                    <!-- Section: Links  -->
                    <div class="d-none d-lg-block">
                        <section class="text-white">
                            <div class="container text-center text-md-start mt-5">
                                <!-- Grid row -->
                                <div class="row mt-3">
                                    <!-- Grid column -->
                                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                                        <!-- Content -->
                                        <h6 class="text-uppercase fw-bold mb-4">
                                            <i class="fa-solid fa-shop me-2"></i>MKH Rental
                                        </h6>
                                        <p>
                                            Here you can use rows and columns to organize your footer content. Lorem ipsum
                                            dolor sit amet, consectetur adipisicing elit.
                                        </p>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                                        <!-- Links -->
                                        <h6 class="text-uppercase fw-bold mb-4">
                                            Products
                                        </h6>
                                        <p>
                                            <a href="#!" class="text-reset">Excavators</a>
                                        </p>
                                        <p>
                                            <a href="#!" class="text-reset">Excavator Attachments</a>
                                        </p>
                                        <!-- <p>
                                            <a href="#!" class="text-reset">Trucks</a>
                                        </p> -->
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                                        <!-- Links -->
                                        <h6 class="text-uppercase fw-bold mb-4">
                                            Useful links
                                        </h6>
                                        <p>
                                            <a href="products.php" class="text-reset">Products</a>
                                        </p>
                                        <p>
                                            <a href="profilesetting.php" class="text-reset">Profile Settings</a>
                                        </p>
                                        <p>
                                            <a href="aboutus.php" class="text-reset">About Us</a>
                                        </p>
                                        <p>
                                            <a class="text-reset logout-link">Log Out</a>
                                        </p>
                                    </div>
                                    <!-- Grid column -->

                                    <!-- Grid column -->
                                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                                        <!-- Links -->
                                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                                        <p><i class="fas fa-home me-3"></i> Insein Township, Yangon, Myanmar</p>
                                        <p>
                                            <i class="fas fa-envelope me-3"></i>
                                            admin@admin.com
                                        </p>
                                        <p><i class="fas fa-phone me-3"></i> + 95 511 71 26</p>
                                        <p><i class="fas fa-phone me-3"></i> + 95 771 511 059</p>
                                    </div>
                                    <!-- Grid column -->
                                </div>
                                <!-- Grid row -->
                            </div>
                        </section>
                    </div>
                    <!-- Section: Links  -->

                    <!-- Copyright -->
                    <div class="text-center text-white p-4" style="background-color: rgb(20, 24, 28);">
                        © 2024 Copyright:
                        <a class="text-reset fw-bold" href="#">MKHRental.com</a>
                    </div>
                    <!-- Copyright -->
                </footer>


            </div>
        </div>
    </section>
    <?php include 'chatbot.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingScreen = document.getElementById('loadingScreen');

            // Set a delay of 2 or 3 seconds before hiding the loading screen
            setTimeout(function() {
                loadingScreen.style.display = 'none'; // Hide loading screen after delay
            }, 1200); // 2000 milliseconds = 2 seconds
            // or you can use 3000 for 3 seconds delay
            // }, 3000);
        });

        const hamBurger = document.querySelector(".toggle-btn");
        const sidebar = document.querySelector("#sidebar");
        const mainContent = document.querySelector(".main");

        function checkScreenWidth() {
            if (window.innerWidth >= 1200) {
                sidebar.classList.add("expand");
                mainContent.classList.add("expanded");
                mainContent.classList.remove("collapsed");
            } else {
                sidebar.classList.remove("expand");
                mainContent.classList.remove("expanded");
                mainContent.classList.add("collapsed");
            }
        }

        window.addEventListener("resize", checkScreenWidth);
        document.addEventListener("DOMContentLoaded", checkScreenWidth);

        hamBurger.addEventListener("click", function() {
            sidebar.classList.toggle("expand");
            mainContent.classList.toggle("expanded");
            mainContent.classList.toggle("collapsed");

            // Close all dropdowns when the sidebar collapses
            if (!sidebar.classList.contains("expand")) {
                const openDropdowns = document.querySelectorAll(".sidebar-dropdown.collapse.show");
                openDropdowns.forEach(dropdown => {
                    const bsCollapse = new bootstrap.Collapse(dropdown, {
                        toggle: true
                    });
                    bsCollapse.hide();
                });
            }
        });

        // Handle sidebar link clicks
        const sidebarLinks = document.querySelectorAll(".sidebar-link");
        sidebarLinks.forEach(link => {
            link.addEventListener("click", function(event) {
                if (window.innerWidth < 1200 && sidebar.classList.contains("expand")) {
                    // Check if the clicked link is not a dropdown toggle
                    if (!link.getAttribute('data-bs-toggle') || link.getAttribute('data-bs-toggle') !== 'collapse') {
                        // Collapse the sidebar
                        sidebar.classList.remove("expand");
                        mainContent.classList.remove("expanded");
                        mainContent.classList.add("collapsed");

                        // Close all dropdowns
                        const openDropdowns = document.querySelectorAll(".sidebar-dropdown.collapse.show");
                        openDropdowns.forEach(dropdown => {
                            const bsCollapse = new bootstrap.Collapse(dropdown, {
                                toggle: true
                            });
                            bsCollapse.hide();
                        });
                    }
                }

                // Set the clicked link as active
                sidebarLinks.forEach(link => link.classList.remove("active"));
                link.classList.add("active");
            });
        });

        // Handle dropdown menu items
        const dropdownItems = document.querySelectorAll(".sidebar-dropdown .sidebar-link");
        dropdownItems.forEach(item => {
            item.addEventListener("click", function() {
                if (window.innerWidth < 1200 && sidebar.classList.contains("expand")) {
                    sidebar.classList.remove("expand");
                    mainContent.classList.remove("expanded");
                    mainContent.classList.add("collapsed");

                    // Close all dropdowns
                    const openDropdowns = document.querySelectorAll(".sidebar-dropdown.collapse.show");
                    openDropdowns.forEach(dropdown => {
                        const bsCollapse = new bootstrap.Collapse(dropdown, {
                            toggle: true
                        });
                        bsCollapse.hide();
                    });
                }

                // Set the clicked dropdown item as active
                dropdownItems.forEach(item => item.classList.remove("active"));
                item.classList.add("active");
            });
        });

        $(window).on('load', function() {
            setTimeout(function() {
                $('.sidebar-link.filter-option').each(function() {
                    if ($(this).text().trim() === "All Products") {
                        $(this).trigger('click');
                        $('.sidebar-link').removeClass('active'); // Remove active class from all links
                        $(this).addClass('active'); // Set this link as active manually
                    }
                });
            }, 500);
        });


        let adminMap;
        let adminMarker;

        function initAdminMap(latLng) {
            adminMap = new google.maps.Map(document.getElementById("adminMap"), {
                center: latLng,
                zoom: 15,
            });

            adminMarker = new google.maps.Marker({
                position: latLng,
                map: adminMap,
            });
        }




        // Function to update the saved products badge
        function updateSavedProductsCount() {
            $.ajax({
                url: 'api/getSavedProductsCountAPI.php',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        var count = response.saved_count;
                        $('.badge-number').text(count);
                    } else {
                        // Swal.fire({
                        //     icon: 'error',
                        //     title: 'Error',
                        //     text: 'Failed to retrieve saved products count: ' + response.message,
                        //     confirmButtonText: 'OK'
                        // });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching the saved products count.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Call the function to update the badge on page load
        updateSavedProductsCount();

        var urlParams = new URLSearchParams(window.location.search);
        var searchQuery = urlParams.get('query');

        if (searchQuery) {
            $('#searchInput').val(searchQuery);
            searchProducts();
        }

        // Load saved products on page ready
        $('#allproducts').ready(function() {
            $.ajax({
                url: "api/SavedProductsAPI.php",
                method: "POST",
                data: {},
                dataType: "json",
                success: function(savedProducts) {
                    const savedProductIds = savedProducts.map(savedProduct => savedProduct.product_id);
                    console.log(savedProductIds);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load saved products!',
                        confirmButtonText: 'OK'
                    });
                }
            });

            $('#searchproduct').on('click', function() {
                $('#searchInputMobile').val(''); // Clear the mobile search input
                searchProducts();
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#searchInputMobile').val(''); // Clear the mobile search input
                    searchProducts();
                }
            });

            // Main content search input for smaller screens
            $('#searchproductMobile').on('click', function() {
                $('#searchInput').val(''); // Clear the navbar search input
                searchProducts();
            });

            $('#searchInputMobile').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#searchInput').val(''); // Clear the navbar search input
                    searchProducts();
                }
            });

            // Attach click event to filter options
            $('.filter-option').on('click', function() {
                var filterType = $(this).data('filter');
                var filterValue = $(this).data('value');
                var extraFilter = $(this).data('extra-filter');
                var extraValue = $(this).data('extra-value');

                // Search products based on filter parameters
                searchProducts(filterType, filterValue, extraFilter, extraValue);

                // Update the breadcrumb
                var breadcrumbText = $(this).text().trim();
                $('.currentbreadcrumb').text(breadcrumbText);

                // Update the filter header text based on the specific link clicked
                var filterHeaderText = $(this).text().trim(); // Get the text of the clicked link
                if (filterHeaderText === "All Products") {
                    $('.filter_header').text(""); // Clear text if 'All Products' is clicked
                } else {
                    $('.filter_header').text(" | Filter by: " + filterHeaderText); // Add 'Filter by: ' prefix otherwise
                }
            });
        });

        window.toggleSave = function(product_id, button) {
            var user_id = $(button).data('userid');
            if (user_id) {
                $.ajax({
                    method: "POST",
                    url: "api/toggleSavedAPI.php",
                    data: {
                        user_id: user_id,
                        product_id: product_id
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire({
                            icon: response.success ? 'success' : 'error',
                            title: response.success ? 'Saved' : 'Error',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });

                        var heartIcon = $(button).find('i');
                        heartIcon.removeClass('bi-bookmark bi-bookmark-fill');
                        heartIcon.addClass(response.isSaved ? 'bi-bookmark-fill' : 'bi-bookmark');
                        updateSavedProductsCount();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "AJAX request failed with status: " + status + "\nError: " + error,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Log in required',
                    text: "Please Log in to Save.",
                    confirmButtonText: 'Log In',
                    cancelButtonText: 'Cancel',
                    showCancelButton: true,
                    focusConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'signin.php'; // Redirect to the login page
                    }
                });




            }
        };

        function searchProducts(filterType, filterValue, extraFilter, extraValue) {
            var searchQuery = $('#searchInput').val() || $('#searchInputMobile').val();
            var filterData = {};
            if (filterType && filterValue) {
                filterData[filterType] = filterValue;
                if (extraFilter && extraValue) {
                    filterData[extraFilter] = extraValue;
                }
            }

            $('#searchFormContainer').addClass('searched');

            $.ajax({
                url: 'api/searchAPI.php',
                type: 'POST',
                data: {
                    search: searchQuery,
                    filters: filterData
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    window.scrollTo(0, 0);
                    displaySearchResults(data);
                },
                error: function(error) {
                    console.log('Error during search:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Search Error',
                        text: 'An error occurred during search. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function displaySearchResults(data) {
            var displayResultsContainer = $("#displaySearchResults");
            displayResultsContainer.empty();

            var resultProductsSection = $('<div class="d-block d-md-block d-lg-block"></div>');
            var resultProductsRow = $('<div class="row"></div>');

            $.each(data, function(index, product) {
                var productId = product.product_id;
                var isSaved = product.is_saved; // Assuming the API returns whether the product is saved or not
                var heartIconClass = isSaved ? 'bi-bookmark-fill' : 'bi-bookmark';
                var activeIconClass = product.is_active ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger';
                var rentButton = product.is_active ? '<a  class="btn btn-success btn-md card-link" id="rentbutton">Rent</a>' : '<button class="btn btn-secondary btn-md card-link" id="viewinfo"><i class="bi bi-info-circle"></i></button>';
                var rating = product.rating ? product.rating : 0;
                var imgsrc = `api/viewproductImgAPI.php?product_id=${encodeURIComponent(productId)}`;

                var resultProductCard = $(
                    '<div class="col-lg-3 col-md-4 col-sm-6 col-12 my-2 d-flex justify-content-center align-items-center product-card" data-product-id="' + productId + '">' +
                    '   <div class="card d-flex flex-column">' +
                    '     <div class="img-container">' +
                    '       <img src="' + imgsrc + '" class="card-img-top" alt="..." style="width: 100%; height: 100%; object-fit: cover;">' +
                    '     </div>' +
                    '       <div class="card-body d-flex justify-content-start">' +
                    '           <h5 class="card-title"><i class="bi ' + activeIconClass + ' "></i> ' + product.product_name + '</h5>' +
                    '               <hr class="divider-custom">' +
                    '           <div class="card-text mt-1">' +
                    '               <p>Class : ' + product.product_type + '</p>' +
                    '               <p>Type : ' + product.type + '</p>' +
                    '               <p class ="fst-italic fw-bold">' + product.usage_conditions + '</p>' +
                    '               <p><div class="fixed-rating">' + generateStarRating(rating) + ' ' + rating + ' stars' + '</div></p>' +
                    '           </div>' +
                    '           <div class="d-flex justify-content-center align-items-center">' +
                    rentButton +
                    '               <button class="btn btn-lg" data-userid="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" onclick="toggleSave(' + product.product_id + ', this)">' +
                    '                   <i class="text-warning bi ' + heartIconClass + '"></i>' +
                    '               </button>' +
                    '               <a  class="btn btn-primary btn-md card-link" id="morebutton">More</a>' +
                    '           </div>' +
                    '       </div>' +
                    '   </div>' +
                    '</div>'
                );

                resultProductCard.find('#morebutton').on('click', function() {
                    redirectToProductDetails(productId);
                });

                resultProductCard.find('#viewinfo').on('click', function() {
                    ViewRentInformation(productId);
                });

                resultProductCard.find('#rentbutton').on('click', function() {
                    redirectToRentForm(productId);
                });

                resultProductsRow.append(resultProductCard);
            });

            resultProductsSection.append(resultProductsRow);
            displayResultsContainer.append(resultProductsSection);
        }

        function ViewRentInformation(productId) {
            var user_id = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
            if (user_id) {
                var product_id = productId;
                console.log(product_id);

                $.ajax({
                    url: 'api/viewRentInfo.php',
                    type: 'POST',
                    data: {
                        product_id: product_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Check if response contains data and is not an error
                        if (response && !response.error) {
                            console.log(response);

                            // Extract latitude and longitude from the response
                            var latLng = {
                                lat: parseFloat(response.latitude),
                                lng: parseFloat(response.longitude)
                            };
                            initAdminMap(latLng);

                            // Update modal with the received data
                            $('#city').text(response.rcity);
                            $('#township').text(response.rtownship);
                            $('#start_date').text(response.start_date);
                            $('#end_date').text(response.end_date);


                            // Show the modal
                            $('#viewRentInfoModal').modal('show');
                            const googleMapsUrl = `https://www.google.com/maps?q=${latLng.lat},${latLng.lng}`;
                            $('#openInGoogleMaps').off('click').on('click', function(e) {
                                e.preventDefault();

                                Swal.fire({
                                    title: 'View in Google Maps?',
                                    text: "Do you want to view this location in Google Maps?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Open',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.open(googleMapsUrl, '_blank');
                                    }
                                });
                            });
                        } else {
                            // If no data is found, display a warning
                            Swal.fire({
                                icon: 'info',
                                title: 'No Data',
                                text: response.error || 'No rental information found for this product.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(error) {
                        console.log('Error during search:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Search Error',
                            text: 'An error occurred during search. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Log in required',
                    text: "Please Log in to view rent information.",
                    confirmButtonText: 'Log In',
                    cancelButtonText: 'Cancel',
                    showCancelButton: true,
                    focusConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'signin.php'; // Redirect to the login page
                    }
                });
            }
        }



        function redirectToProductDetails(productId) {
            if (productId) {
                window.location.href = 'product_details.php?product_id=' + productId;
            } else {
                console.error('Product ID is undefined');
            }
        }

        function redirectToRentForm(productId) {
            var user_id = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
            if (user_id) {
                window.location.href = 'rentform.php?product_id=' + productId;
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Log in required',
                    text: "Please Log in to Rent.",
                    confirmButtonText: 'Log In',
                    cancelButtonText: 'Cancel',
                    showCancelButton: true,
                    focusConfirm: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'signin.php'; // Redirect to the login page
                    }
                });
            }
        }


        function generateStarRating(rating) {
            var starRating = '';
            var fullStars = Math.floor(rating); // Full stars
            var hasHalfStar = rating % 1 >= 0.5; // Determine if there's a half star

            // Generate full stars
            for (var i = 1; i <= fullStars; i++) {
                starRating += '<i class="bi bi-star-fill text-warning"></i>';
            }

            // Generate half star if needed
            if (hasHalfStar) {
                starRating += '<i class="bi bi-star-half text-warning"></i>';
            }

            // Generate empty stars
            for (var i = fullStars + (hasHalfStar ? 1 : 0); i < 5; i++) {
                starRating += '<i class="bi bi-star text-warning"></i>';
            }

            return starRating;
        }
    </script>
    <?php include 'logout-link.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>