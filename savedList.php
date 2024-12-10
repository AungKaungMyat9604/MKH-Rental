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
    <title>MKH Rental_Saved List</title>
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
    <link rel="stylesheet" href="css/savedList.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&libraries=places&callback=initMap"
        defer></script>
    <style>
        .modal-content {
        background: rgba(189, 195, 199);
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
            z-index: 1000;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            /* Smooth transition */
        }

        .accessibility-circle:hover {
            transform: scale(1.1);
            /* Enlarge on hover */
        }

        /* Circle Menu */
        .circle-menu {
            display: none;
            /* Hidden by default */
            position: fixed;
            bottom: 90px;
            left: 35px;
            z-index: 1001;
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
            transition: transform 0.3s ease;
            /* Smooth transition */
        }

        .circle-button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
            /* Enlarge on hover */
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

                            <li><a class="dropdown-item" href="signout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="nav-item dropdown d-flex align-items-center ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-md-block me-3 text-white">
                                <i class="fa-solid fa-caret-down p-1"></i>Sign In
                            </span>
                            <span class="d-block d-md-none me-2 text-white">
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                            <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Profile" class="rounded-circle img-fluid" style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
                        <a class="nav-link active" href="products.php">Products</a>
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
    <div class="main" id="main">
        <main class="content px-1 py-4" style="background-color: rgb(179, 185, 189);">

            <nav aria-label="breadcrumb" class="breadcrumb mx-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="savedList.php">Saved List</a></li>
                </ol>
            </nav>
            <div class="mx-4 mb-3">
                <button id="backButton" class="btn btn-secondary" style="position:fixed; z-index:99999;" onclick="goBack()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
            <div class="container-fluid">
                <div class="container" id="searchFormContainer">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="fw-bold fs-1 mb-4"><span class="text-warning"><i class="fa-solid fa-bookmark me-2"></i></span> Saved List <span class="text-muted ps-3 fs-3">| by <?php echo $_SESSION['fullname']; ?></span></h2>
                        </div>
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
        </main>
    </div>
    <div class="modal fade" id="viewRentInfoModal" tabindex="-1" aria-labelledby="viewLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content px-md-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRentInfoModalLabel">Where and Who?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 mt-3">
                        <img id="viewPF" src="api/viewprofilepictureAPI.php?user_id=" alt="Click to Upload" class="text-start rounded-circle img-fluid mx-auto d-block" style="background-color: white; width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Renter: <span id="fullname"></span></label>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Renter Township: <span id="township"></span></label>
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

    <!-- Footer -->

    <footer class="text-center text-lg-start bg-dark text-muted" style="margin-top:70px;">
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
                            <p>
                                <a href="#!" class="text-reset">Trucks</a>
                            </p>
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
                                <a class="text-reset logout-link">Sign Out</a>
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
                            <p><i class="fas fa-print me-3"></i> + 95 771 511 059</p>
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
    <?php include 'chatbot.php'; ?>
    <script>
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
        $(document).ready(function() {

            window.scrollTo(0, 0);
            fetchSavedProducts();

            $('#viewRentInfoModal').modal('hide');

            function fetchSavedProducts() {
                $.ajax({
                    url: "api/SavedProductsAPI.php",
                    method: "POST",
                    data: {},
                    dataType: "json",
                    success: function(savedProducts) {
                        const savedProductIds = savedProducts.map(savedProduct => savedProduct.product_id);
                        displaySavedProducts(savedProductIds);
                        console.log('fetch');
                    },
                    error: function() {
                        alert("Failed to load saved products!");
                    }
                });
            }

            function displaySavedProducts(savedProductIds) {
                const displayResultsContainer = $("#displaySearchResults");
                displayResultsContainer.empty();

                $.ajax({
                    url: "api/allproductsAPI.php",
                    method: "POST",
                    data: {},
                    dataType: "json",
                    success: function(data) {
                        console.log('saved products loading');
                        const savedProducts = data.filter(product => savedProductIds.includes(parseInt(product.product_id)));
                        const resultProductsSection = $('<div class="d-block d-md-block d-lg-block"></div>');
                        const resultProductsRow = $('<div class="row"></div>');

                        savedProducts.forEach(product => {
                            const isSaved = savedProductIds.includes(parseInt(product.product_id));
                            const heartIconClass = isSaved ? 'bi-bookmark-fill' : 'bi-bookmark';
                            const activeIconClass = product.is_active ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger';
                            var rentButton = product.is_active ? '<a  class="btn btn-success btn-md card-link" id="rentbutton">Rent</a>' : '<button class="btn btn-secondary btn-md card-link" id="viewinfo"><i class="bi bi-info-circle"></i></button>';
                            const rating = product.rating || 0;

                            const resultProductCard = $(
                                `<div class="col-lg-3 col-md-4 col-sm-6 col-12 my-2 d-flex justify-content-center align-items-center product-card" data-product-id="${product.product_id}">
                            <div class="card d-flex flex-column">
                                <div class="img-container">
                                    <img src="api/viewproductimgAPI.php?product_id=${encodeURIComponent(product.product_id)}" class="card-img-top" alt="..." style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="card-body d-flex justify-content-start">
                                    <h5 class="card-title"><i class="bi ${activeIconClass}"></i> ${product.product_name}</h5>
                                                                            <hr class="divider-custom">
                                    <div class="card-text mt-1">
                                        <p>Class : ${product.product_type}</p>
                                        <p>Type : ${product.type}</p>
                                        <p class ="fst-italic fw-bold">${product.usage_conditions}</p>
                                        <p><div class="fixed-rating">${generateStarRating(rating)} ${rating} stars</div></p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        ${rentButton}
                                        <button class="btn btn-lg" data-userid="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" onclick="toggleSave(${product.product_id}, this)">
                                            <i class="text-warning bi ${heartIconClass}"></i>
                                        </button>
                                        <a href="#" class="btn btn-primary btn-md card-link" id="morebutton">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>`
                            );

                            resultProductCard.find('#morebutton').on('click', function() {
                                redirectToProductDetails(product.product_id);
                            });

                            resultProductCard.find('#viewinfo').on('click', function() {
                                ViewRentInformation(product.product_id);
                            });

                            resultProductCard.find('#rentbutton').on('click', function() {
                                redirectToRentForm(product.product_id);
                            });

                            resultProductsRow.append(resultProductCard);
                        });

                        resultProductsSection.append(resultProductsRow);
                        displayResultsContainer.append(resultProductsSection);
                    },
                    error: function() {
                        alert("Failed to load products!");
                    }
                });
            }

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
                                $('#fullname').text(response.fullname);
                                $('#township').text(response.township);
                                $('#start_date').text(response.start_date);
                                $('#end_date').text(response.end_date);

                                // Update the profile picture source
                                $('#viewPF').attr('src', 'api/viewprofilepictureAPI.php?user_id=' + response.user_id);

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
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(); // Reload the page when the OK button is clicked
                                }
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

            function redirectToProductDetails(productId) {
                if (productId) {
                    window.location.href = 'product_details.php?product_id=' + productId;
                } else {
                    console.error('Product ID is undefined');
                }
            }

            function redirectToRentForm(productId) {
                if (productId) {
                    window.location.href = 'rentform.php?product_id=' + productId;
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
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>