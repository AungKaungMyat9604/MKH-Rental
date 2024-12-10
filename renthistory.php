<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    $user_id = $_SESSION['user_id'];
    $fullnameParts = explode(" ", $_SESSION['fullname']);
    $firstName = $fullnameParts[0];
} else {
    $user_id = '';
}
if ($user_id){
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<head>
    <title>MKH Rental_Rent History</title>
    <link rel="icon" type="image/png" href="images/small_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
    <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/rentHistory.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
            .dropdown-item:hover {
        background-color: #f8f9fa;
        /* Light gray background */
        color: #007bff;
        /* Bootstrap primary color for text */
      }
      
        .loading-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            /* Slightly transparent background */
            z-index: 9999;
            /* Ensure it's on top of all other content */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Spinner (You can customize the spinner as needed) */
        .spinner {
            border: 12px solid #f3f3f3;
            /* Light grey */
            border-top: 12px solid cyan;
            /* Blue */
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
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
    z-index: 1003;
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
    z-index: 1003;
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
    <!--NAVBAR-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index:999;">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="home.php">
                <img src="images/Logo.png" class="img-fluid" alt="MKH Group" style="width: auto; height: 55px; margin-left: 5px; border-radius: 15px;" />
            </a>

            <div class="d-flex align-items-center order-lg-2">
                <?php if (isset($_SESSION['email'])) { ?>
                    <li class="nav-item">
                        <a class="ms-4" href="savedList.php">
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

                            <li><a class="dropdown-item logout-link"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a></li>
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
                            <li><a class="dropdown-item" href="signin.php"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</a></li>
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
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About Us</a>
                    </li>
                </ul>

                <form class="d-flex d-none d-lg-block" id="searchForm" action="products.php" method="GET" style="margin-top:auto; margin-bottom: auto;">
                    <div class="input-group mx-2">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput" name="query" />
                        <button id="searchproduct" class="btn btn-outline-secondary" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>


    <!--Main Content-->
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
                        <a href="">Status</a>
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a  class="sidebar-link filter-option" data-filter="" data-value="">
                            <img src="images/all.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">All Status</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a  class="sidebar-link filter-option" data-filter="rent_status" data-value="pending">
                            <img src="images/pending.png" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Pending</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a  class="sidebar-link filter-option" data-filter="rent_status" data-value="approved / ongoing">
                            <img src="images/approved.png" class="" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Approved / Ongoing</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a  class="sidebar-link filter-option" data-filter="rent_status" data-value="declined">
                            <img src="images/declined.png" class="" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Declined</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a  class="sidebar-link filter-option" data-filter="rent_status" data-value="finished">
                            <img src="images/finished.png" class="" alt="Excavator Icon" width="30" height="30">
                            <span class="mx-2">Finished</span>
                        </a>
                    </li>
                </ul>
            </aside>
            <div class="main" id="main">
                <main class="content px-1 py-4" style="background-color: rgb(179, 185, 189);">
                    <div class="container-fluid">
                        <nav aria-label="breadcrumb" class="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="renthistory.php">History</a></li>
                            </ol>
                        </nav>
                        <div class="container d-flex align-items-start ps-2 mb-3">
                            <button id="backButton" class="btn btn-outline-secondary" onclick="goBack()">
                                <i class="bi bi-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="container" id="searchFormContainer">
                            <div class="row align-items-center">
                                <div class="col-sm-5">
                                    <h2 class="fw-bold fs-1">Rent History</h2>
                                </div>
                            </div>
                        </div>
                        <section id="displayFilterResult" class="container">
                            <section id="allhistorys" class="container-lg" style="padding: 10px;">
                                <div class="d-block d-md-block d-lg-block">
                                    <div class="row" id="allhistorysdisplay"></div>
                                </div>
                            </section>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="whyModal" tabindex="-1" aria-labelledby="whyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whyModalLabel">Reason for Decline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- This is where you can dynamically insert the reason for the decline -->
                    <p id="declineReason">The reason for the decline will be displayed here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Hour Extension -->
    <div class="modal fade" id="extensionModal" tabindex="-1" aria-labelledby="extensionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="extensionModalLabel">Hour Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="extensionProductId" value="">

                    <div class="mb-3">
                        <label for="newHours" class="form-label">Additional Hours: </label>
                        <input type="number" class="form-control" id="newHours" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Hours: <span id="totalHours"></span></label>
                        <!-- This will display the current or updated end date -->
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date: <span id="endDate"></span></label>
                        <!-- This will display the current or updated end date -->
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Cost: <span id="totalCost"></span></label>
                        <!-- This will display the current or updated total cost -->
                    </div>
                    <input type="hidden" id="extensionRentId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveExtension">Upload</button>
                </div>
            </div>
        </div>
    </div>

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

        function updateSavedProductsCount() {
            $.ajax({
                url: 'api/getSavedProductsCountAPI.php',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        var count = response.saved_count;
                        $('.badge-number').text(count);
                    } else {
                        console.error('Failed to retrieve saved products count:', response.message);
                    }
                },
                error: function() {
                    console.error('An error occurred while fetching the saved products count.');
                }
            });
        }

        // Call the function to update the badge on page load
        updateSavedProductsCount();


        function goBack() {
            window.history.back();
        }
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
                    if ($(this).text().trim() === "All Status") {
                        $(this).trigger('click');
                        $('.sidebar-link').removeClass('active'); // Remove active class from all links
                        $(this).addClass('active'); // Set this link as active manually
                    }
                });
            }, 500);
        });




        $(document).ready(function() {
            // Load saved products on page ready
            // $('#allhistorys').ready(function() {
            //     var allHistorysContainer = $("#allhistorysdisplay");
            //     allHistorysContainer.empty();
            //     $.ajax({
            //         url: "api/allhistorysAPI.php",
            //         method: "POST",
            //         data: {},
            //         dataType: "json",
            //         success: function(data) {
            //             $.each(data, function(index, history) {
            //                 var cancelButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-md card-link cancelbutton" >Cancel</a>' : '';
            //                 var whyButton = history.rent_status === "declined" ?
            //                     '<a  class="btn btn-primary btn-md card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';

            //                 var extensionButton = (history.rent_status === "approved / ongoing") ? '<a  class="btn btn-primary btn-md card-link extensionbutton"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
            //                 var rentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a  class="btn btn-primary btn-md card-link rentbutton" ><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

            //                 var scancelButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-sm card-link cancelbutton">Cancel</a>' : '';
            //                 var swhyButton = history.rent_status === "declined" ?
            //                     '<a  class="btn btn-primary btn-sm card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';
            //                 var sextensionButton = (history.rent_status === "approved / ongoing") ? '<a  class="btn btn-primary btn-sm card-link extensionbutton"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
            //                 var srentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a class="btn btn-primary btn-sm card-link rentbutton"><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

            //                 var allHistoryCard = $(
            //                     '<div class="my-2 d-flex justify-content-center align-items-center history-card">' +
            //                     '   <div class="card col-12 col-md-8 d-lg-none">' + // Small screen card
            //                     '       <div class="card-body p-4">' +
            //                     '           <div class="row d-flex justify-content-center align-items-center">' +
            //                     '               <div class="col-12 col-md-8 d-flex justify-content-start align-items-center">' +
            //                     '                   <h5 class="card-title fw-bold fs-4 pe-3" style="border-right:3px solid black;">' + history.product_name + '</h5>' +
            //                     '                   <span class="text-muted ps-3 fs-5">' + history.product_type + '</span>' +
            //                     '               </div>' +
            //                     '           </div>' +
            //                     '           <div class="d-flex justify-content-end align-items-center mt-2 mt-md-0">' +
            //                     '               <p class="fw-bold text-uppercase text-decoration-underline">"' + history.rent_status + '"</p>' +
            //                     '           </div>' +
            //                     '           <hr class="divider-custom mt-1">' +
            //                     '           <div class="card-text mt-1">' +
            //                     '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
            //                     '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
            //                     '               <div class="row mt-2">' +
            //                     '                   <div class="col-12 col-md-6">' +
            //                     '                       <p>Total Hour : ' + history.renting_hours + ' Hours</p>' +
            //                     '                   </div>' +
            //                     '                   <div class="col-12 col-md-6 d-flex justify-content-end">' +
            //                     '                       <p>Total Cost (Renting Only) : ' + history.total_cost + ' Kyats</p>' +
            //                     '                   </div>' +
            //                     '               </div>' +
            //                     '               <hr class="divider-custom mt-3">' +
            //                     '               <div class="row mt-3">' +
            //                     '                   <div class="col-12 col-md-4">' +
            //                     '                       <p>Posted at ' + history.posted_datetime + '</p>' +
            //                     '                   </div>' +
            //                     '               </div>' +
            //                     '               <div class="d-flex justify-content-center align-items-center">' + // Stack buttons on small screens
            //                     '                   <a  class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
            //                     scancelButton +
            //                     swhyButton +
            //                     srentButton +
            //                     sextensionButton +
            //                     '               </div>' +
            //                     '           </div>' +
            //                     '       </div>' +
            //                     '   </div>' +
            //                     '   <div class="card col-12 col-md-8 d-none d-lg-block">' + // Large screen card
            //                     '       <div class="card-body p-4">' +
            //                     '           <div class="row d-flex justify-content-center align-items-center">' +
            //                     '               <div class="col-12 col-md-8 d-flex justify-content-start align-items-center">' +
            //                     '                   <h5 class="card-title fw-bold fs-3 pe-3" style="border-right:3px solid black;">' + history.product_name + '</h5>' +
            //                     '                   <span class="text-muted ps-3 fs-5">' + history.product_type + '</span>' +
            //                     '               </div>' +
            //                     '               <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">' +
            //                     '                   <h5 class="fw-bold text-uppercase text-decoration-underline">"' + history.rent_status + '"</h5>' +
            //                     '               </div>' +
            //                     '           </div>' +
            //                     '           <hr class="divider-custom mt-2">' +
            //                     '           <div class="card-text mt-2">' +
            //                     '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
            //                     '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
            //                     '               <div class="row mt-2">' +
            //                     '                   <div class="col-12 col-md-6">' +
            //                     '                       <p>Total Hour : ' + history.renting_hours + ' Hours</p>' +
            //                     '                   </div>' +
            //                     '                   <div class="col-12 col-md-6 d-flex justify-content-end">' +
            //                     '                       <p>Total Cost (Renting Only) : ' + history.total_cost + ' Kyats</p>' +
            //                     '                   </div>' +
            //                     '               </div>' +
            //                     '               <hr class="divider-custom mt-3">' +
            //                     '               <div class="row mt-3">' +
            //                     '                   <div class="col-12 col-md-4">' +
            //                     '                       <p>Posted at ' + history.posted_datetime + '</p>' +
            //                     '                   </div>' +
            //                     '                   <div class="col-12 col-md-8">' +
            //                     '                       <div class="d-flex justify-content-end align-items-center flex-column flex-md-row">' + // Stack buttons on small screens
            //                     '                           <a  class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
            //                     cancelButton +
            //                     whyButton +
            //                     rentButton +
            //                     extensionButton +
            //                     '                       </div>' +
            //                     '                   </div>' +
            //                     '               </div>' +
            //                     '           </div>' +
            //                     '       </div>' +
            //                     '   </div>' +
            //                     '</div>'
            //                 );

            //                 // Handling the click events
            //                 allHistoryCard.find('.viewbutton').on('click', function() {
            //                     redirectToProductDetails(history.product_id);

            //                 });

            //                 allHistoryCard.find('.cancelbutton').on('click', function() {
            //                     cancelRentForm(history.rent_forms_id, history.product_id);
            //                 });

            //                 // Update the click event for the #whybutton to show the modal
            //                 allHistoryCard.find('.whybutton').on('click', function() {
            //                     // If the decline reason is available, set it in the modal
            //                     var declineReason = history.message_why || "No specific reason provided."; // Replace with actual reason
            //                     $('#whyModal .modal-body #declineReason').text(declineReason);
            //                 });

            //                 allHistoryCard.find('.rentbutton').on('click', function() {
            //                     redirectToRentForm(history.product_id);
            //                 });

            //                 allHistoryCard.find('.extensionbutton').on('click', function() {
            //                     $('#extensionModal').show();
            //                     $('#extensionProductId').val(history.product_id);
            //                     $('#extensionRentId').val(history.rent_forms_id);
            //                     $('#totalHours').text(history.renting_hours + ' Hrs');
            //                     $('#endDate').text(history.end_date); // Show the current end date
            //                     $('#totalCost').text(history.total_cost + ' Kyats'); // Show the current total cost
            //                     console.log(history.rent_forms_id);
            //                 });

            //                 allHistorysContainer.append(allHistoryCard);
            //             });
            //         }
            //     });

                // Handle the "Save changes" button click in the Hour Extension modal
                $('#newHours').on('input', function() {
                    var additionalHours = $(this).val();
                    var rent_form_id = $('#extensionRentId').val();
                    var product_id = $('#extensionProductId').val();

                    if (additionalHours && rent_form_id) {
                        $.ajax({
                            url: 'api/calculateNewCostAndEndDate.php', // The endpoint to calculate the new cost and end date
                            method: 'POST',
                            data: {
                                product_id: product_id,
                                rent_form_id: rent_form_id,
                                additional_hours: additionalHours
                            },
                            success: function(response) {
                                try {
                                    var data = JSON.parse(response);

                                    if (data.success) {
                                        // Update the UI with the new values
                                        $('#totalHours').text(data.totalHours + ' Hrs');
                                        $('#endDate').text(data.new_end_date);
                                        $('#totalCost').text(data.new_cost + ' Kyats');
                                        console.log(data.new_end_date);
                                    } else {
                                        alert('Error: ' + (data.error || 'Unknown error occurred.'));
                                    }
                                } catch (e) {
                                    alert('Failed to parse response: ' + e.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                alert("Failed to calculate new end date and cost: " + error);
                            }
                        });
                    } else {

                    }
                });


                // Save the hour extension
                $('#saveExtension').on('click', function(e) {
                    var additionalHours = $('#newHours').val();
                    var rent_form_id = $('#extensionRentId').val();
                    var product_id = $('#extensionProductId').val();

                    e.preventDefault();
                    $('#loadingScreen').show();

                    if (rent_form_id && product_id) {
                        $.ajax({
                                url: 'api/extendHoursAPI.php',
                                method: 'POST',
                                data: {
                                    product_id: product_id,
                                    rent_form_id: rent_form_id,
                                    additional_hours: additionalHours
                                }
                            })
                            .done(function(msg) {
                                $('#loadingScreen').hide(); // Hide the loading screen once the request is done
                                if (msg.trim().startsWith('Success')) {
                                    $('#extensionModal').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Hour extension requested successfully!',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Something went wrong. Please try again.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .fail(function(xhr, status, error) {
                                $('#loadingScreen').hide(); // Hide the loading screen in case of an error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: 'Failed to request hour extension: ' + error,
                                    confirmButtonText: 'OK'
                                });
                            });
                    } else {
                        $('#loadingScreen').hide(); // Hide the loading screen if input validation fails
                        Swal.fire({
                            icon: 'warning',
                            title: 'Missing Information',
                            text: 'Please provide the number of hours.',
                            confirmButtonText: 'OK'
                        });
                    }
                });

                // Attach click event to filter options
                $('.filter-option').on('click', function() {
                    var filterType = $(this).data('filter');
                    var filterValue = $(this).data('value');
                    console.log(filterType, filterValue);
                    filterRentStatus(filterType, filterValue);
                });

            });

            function cancelRentForm(rent_forms_id, product_id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to cancel your Rent Form?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#loadingScreen').show();
                        $.ajax({
                                method: 'POST',
                                url: 'api/cancelRentFormAPI.php',
                                data: {
                                    rent_forms_id: rent_forms_id,
                                    product_id: product_id
                                }
                            })
                            .done(function(msg) {
                                if (msg.trim().startsWith('Success')) {
                                    $('#loadingScreen').hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Cancelled',
                                        text: 'Rent Form canceled successfully.',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    $('#loadingScreen').hide();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to cancel. Please try again.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .fail(function(xhr, status, error) {
                                $('#loadingScreen').hide();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred: ' + error,
                                    confirmButtonText: 'OK'
                                });
                            });
                    }

                });
            }



            function filterRentStatus(filterType, filterValue) {
                var filterData = {};
                if (filterType && filterValue) {
                    filterData[filterType] = filterValue;
                }

                $.ajax({
                    url: 'api/filterRentStatusAPI.php',
                    type: 'POST',
                    data: {
                        filters: filterData
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        window.scrollTo(0, 0);
                        displayFilterResults(data);
                    },
                    error: function(error) {
                        console.log('Error during search:', error);
                    }
                });
            }


            function displayFilterResults(data) {
                var displayResultsContainer = $("#displayFilterResult");
                displayResultsContainer.empty();

                var resultProductsSection = $('<div class="d-block d-md-block d-lg-block"></div>');


                $.each(data, function(index, history) {
                    var cancelButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-md card-link cancelbutton" >Cancel</a>' : '';
                    var whyButton = history.rent_status === "declined" ?
                        '<a  class="btn btn-primary btn-md card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';

                    var extensionButton = (history.rent_status === "approved / ongoing") ? '<a  class="btn btn-primary btn-md card-link extensionbutton"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                    var rentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a  class="btn btn-primary btn-md card-link rentbutton" ><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

                    var scancelButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-sm card-link cancelbutton">Cancel</a>' : '';
                    var swhyButton = history.rent_status === "declined" ?
                        '<a href="#" class="btn btn-primary btn-sm card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';
                    var sextensionButton = (history.rent_status === "approved / ongoing") ? '<a  class="btn btn-primary btn-sm card-link extensionbutton"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                    var srentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a  class="btn btn-primary btn-sm card-link rentbutton"><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

                    var resultHistoryCard = $(
                        '<div class="my-2 d-flex justify-content-center align-items-center history-card">' +
                        '   <div class="card col-12 col-md-8 d-lg-none">' + // Small screen card
                        '       <div class="card-body p-4">' +
                        '           <div class="row d-flex justify-content-center align-items-center">' +
                        '               <div class="col-12 col-md-8 d-flex justify-content-start align-items-center">' +
                        '                   <h5 class="card-title fw-bold fs-4 pe-3" style="border-right:3px solid black;">' + history.product_name + '</h5>' +
                        '                   <span class="text-muted ps-3 fs-5">' + history.product_type + '</span>' +
                        '               </div>' +
                        '           </div>' +
                        '           <div class="d-flex justify-content-end align-items-center mt-2 mt-md-0">' +
                        '               <p class="fw-bold text-uppercase text-decoration-underline">"' + history.rent_status + '"</p>' +
                        '           </div>' +
                        '           <hr class="divider-custom mt-1">' +
                        '           <div class="card-text mt-1">' +
                        '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
                        '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                        '               <p class="mt-2 text-danger">End Date : ' + history.end_date + '</p>' +
                        '               <div class="row mt-2">' +
                        '                   <div class="col-12 col-md-6">' +
                        '                       <p>Total Hour : ' + history.renting_hours + ' Hours</p>' +
                        '                   </div>' +
                        '                   <div class="col-12 col-md-6 d-flex justify-content-end">' +
                        '                       <p>Total Cost (Renting Only) : ' + history.total_cost + ' Kyats</p>' +
                        '                   </div>' +
                        '               </div>' +
                        '               <hr class="divider-custom mt-3">' +
                        '               <div class="row mt-3">' +
                        '                   <div class="col-12 col-md-4">' +
                        '                       <p>Posted at ' + history.posted_datetime + '</p>' +
                        '                   </div>' +
                        '               </div>' +
                        '               <div class="d-flex justify-content-center align-items-center">' + // Stack buttons on small screens
                        '                   <a  class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                        scancelButton +
                        swhyButton +
                        srentButton +
                        sextensionButton +
                        '               </div>' +
                        '           </div>' +
                        '       </div>' +
                        '   </div>' +
                        '   <div class="card col-12 col-md-8 d-none d-lg-block">' + // Large screen card
                        '       <div class="card-body p-4">' +
                        '           <div class="row d-flex justify-content-center align-items-center">' +
                        '               <div class="col-12 col-md-8 d-flex justify-content-start align-items-center">' +
                        '                   <h5 class="card-title fw-bold fs-3 pe-3" style="border-right:3px solid black;">' + history.product_name + '</h5>' +
                        '                   <span class="text-muted ps-3 fs-5">' + history.product_type + '</span>' +
                        '               </div>' +
                        '               <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">' +
                        '                   <h5 class="fw-bold text-uppercase text-decoration-underline">"' + history.rent_status + '"</h5>' +
                        '               </div>' +
                        '           </div>' +
                        '           <hr class="divider-custom mt-2">' +
                        '           <div class="card-text mt-2">' +
                        '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
                        '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                        '               <p class="mt-2 text-danger">End Date : ' + history.end_date + '</p>' +
                        '               <div class="row mt-2">' +
                        '                   <div class="col-12 col-md-6">' +
                        '                       <p>Total Hour : ' + history.renting_hours + ' Hours</p>' +
                        '                   </div>' +
                        '                   <div class="col-12 col-md-6 d-flex justify-content-end">' +
                        '                       <p>Total Cost (Renting Only) : ' + history.total_cost + ' Kyats</p>' +
                        '                   </div>' +
                        '               </div>' +
                        '               <hr class="divider-custom mt-3">' +
                        '               <div class="row mt-3">' +
                        '                   <div class="col-12 col-md-4">' +
                        '                       <p>Posted at ' + history.posted_datetime + '</p>' +
                        '                   </div>' +
                        '                   <div class="col-12 col-md-8">' +
                        '                       <div class="d-flex justify-content-end align-items-center flex-column flex-md-row">' + // Stack buttons on small screens
                        '                           <a  class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                        cancelButton +
                        whyButton +
                        rentButton +
                        extensionButton +
                        '                       </div>' +
                        '                   </div>' +
                        '               </div>' +
                        '           </div>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>'
                    );

                    // Handling the click events
                    resultHistoryCard.find('.viewbutton').on('click', function() {
                        redirectToProductDetails(history.product_id);
                    });

                    resultHistoryCard.find('.cancelbutton').on('click', function() {
                        cancelRentForm(history.rent_forms_id, history.product_id);
                    });

                    // Update the click event for the #whybutton to show the modal
                    resultHistoryCard.find('.whybutton').on('click', function() {
                        // If the decline reason is available, set it in the modal
                        var declineReason = history.message_why || "No specific reason provided."; // Replace with actual reason
                        $('#whyModal .modal-body #declineReason').text(declineReason);
                    });

                    resultHistoryCard.find('.rentbutton').on('click', function() {
                        redirectToRentForm(history.product_id);
                    });

                    resultHistoryCard.find('.extensionbutton').on('click', function() {
                        $('#extensionModal').modal('show');
                        $('#extensionProductId').val(history.product_id);
                        $('#extensionRentId').val(history.rent_forms_id);
                        $('#totalHours').text(history.renting_hours + ' Hrs');
                        $('#endDate').text(history.end_date); // Show the current end date
                        $('#totalCost').text(history.total_cost + ' Kyats'); // Show the current total cost
                    });

                    displayResultsContainer.append(resultHistoryCard);
                });
                displayResultsContainer.append(resultProductsSection);
            }

            function redirectToProductDetails(productId) {
                if (productId) {
                    window.location.href = 'product_details.php?product_id=' + productId;
                } else {
                    console.error('Product ID is undefined');
                }
            }

            function redirectToRentForm(productId) {
                if (productId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to rent this product again?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Go to Rent Form',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'rentform.php?product_id=' + productId;
                        } else {
                            console.log('User cancelled the rent process');
                        }
                    });
                } else {
                    console.error('Product ID is undefined');
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
    <?php include 'logout-link.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>
<?php }
else {
    echo '<script type="text/javascript">
    alert("You cannot access this page. You will be redirected to the Home page.");
    window.location.href = "home.php";
</script>
';
exit();
}