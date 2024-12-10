<?php
session_start();
if (isset($_SESSION['email'])) {
  if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    $user_id = $_SESSION['user_id'];
    $fullnameParts = explode(" ", $_SESSION['fullname']);
    $firstName = $fullnameParts[0];
  } else {
    $user_id = '';
  }

  $productId = isset($_GET['product_id']) ? $_GET['product_id'] : 1;

  if ($productId === null) {
    die("Product ID is not set in the URL.");
  }

  function fetchProductDetailsFromDatabase($productId)
  {
    require 'api/dbinfo.php';

    $sql = "SELECT product_name,
            type,
            product_type,
            cost_per_unit,
            brand,
            ton,
            arm_length,
            bucket_size,
            dimension,
            fuel_tank_capacity,
            weight,
            capacity_size,
            compatible_excavator,
            usage_conditions,
            is_active,
            rating,
            product_id FROM products WHERE product_id = $productId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $conn->close();
      return $row;
    } else {
      $conn->close();
      return null;
    }
  }

  $productDetails = fetchProductDetailsFromDatabase($productId);
  if ($productDetails === null) {
    die("Product not found.");
  }

  // Fetch save status
  $rating = $productDetails['rating'] ? $productDetails['rating'] : 0;
  function generateStarRating($rating)
  {
    $fullStars = floor($rating);
    $hasHalfStar = $rating % 1 >= 0.5;
    $starRating = '';

    for ($i = 1; $i <= $fullStars; $i++) {
      $starRating .= '<i class="bi bi-star-fill text-warning"></i>';
    }

    if ($hasHalfStar) {
      $starRating .= '<i class="bi bi-star-half text-warning"></i>';
    }

    for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++) {
      $starRating .= '<i class="bi bi-star text-warning"></i>';
    }

    return $starRating;
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MKH Rental_Rent Form</title>
    <link rel="icon" type="image/png" href="images/small_logo.png">
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&libraries=places&callback=initMap"
      defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
    <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/rentForm.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
    z-index: 1000;
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
          <img src="images/Logo.png" class="img-fluid" alt="MKH Group"
            style="width: auto; height: 55px; margin-left: 5px; border-radius: 15px;" />
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
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="d-none d-md-block me-3 text-white">
                  <i class="fa-solid fa-caret-down p-1"></i><?php echo $firstName; ?>
                </span>
                <span class="d-block d-md-none me-2 text-white">
                  <i class="fa-solid fa-caret-down"></i>
                </span>
                <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>"
                  alt="Profile" class="rounded-circle img-fluid"
                  style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"
                style="min-width:240px;">
                <li><a class="dropdown-item text-muted text-center"><?php echo $_SESSION['fullname']; ?></a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="profilesetting.php"><i
                      class="fa-regular fa-address-card me-2"></i>Profile Settings</a></li>
                <?php if (isset($_SESSION['email'])) { ?>
                  <li><a href="savedList.php" class="dropdown-item" id="savedDropdown"><i
                        class="fa-solid fa-bookmark me-2"></i> Saved List</a></li>
                  <li><a class="dropdown-item" href="renthistory.php"><i
                        class="fa-solid fa-clock-rotate-left me-2"></i>Rent History</a></li>
                <?php } ?>
                <li>
                  <hr class="dropdown-divider">
                </li>

                <li><a class="dropdown-item" href="#" id="logout-link"><i
                      class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a></li>
              </ul>
            </div>
          <?php } else { ?>
            <div class="nav-item dropdown d-flex align-items-center ms-3">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="d-none d-md-block me-3 text-white">
                  <i class="fa-solid fa-caret-down p-1"></i>Sign In
                </span>
                <span class="d-block d-md-none me-2 text-white">
                  <i class="fa-solid fa-caret-down"></i>
                </span>
                <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>"
                  alt="Profile" class="rounded-circle img-fluid"
                  style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
                <li><a class="dropdown-item" href="signin.php"><i
                      class="fa-solid fa-right-to-bracket me-2"></i>Sign In</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="signup.php"><i
                      class="fa-solid fa-arrow-up-from-bracket me-2"></i>Sign Up</a></li>
              </ul>
            </div>
          <?php } ?>

          <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse order-lg-1" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
          </ul>

          <form class="d-flex d-none d-lg-block" id="searchForm" action="products.php" method="GET"
            style="margin-top:auto; margin-bottom: auto;">
            <div class="input-group mx-2">
              <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                id="searchInput" name="query" />
              <button id="searchproduct" class="btn btn-outline-secondary" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </nav>

    <div id="main" style="margin-top:5rem; padding-top:2rem;">
      <nav aria-label="breadcrumb" class="breadcrumb mx-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          <li class="breadcrumb-item"><a href="products.php">Products</a></li>
          <li class="breadcrumb-item active" aria-current="page"><a href="#">Rent Form</a></li>

        </ol>
      </nav>
      <div class ="mx-4">
        <button id="backButton" class="btn btn-outline-secondary" onclick="goBack()">
          <i class="bi bi-arrow-left"></i> Back
        </button>
        </div>

      <div class="container custom-container">
        <h2 class="text-center mt-3">Rental Form</h2>
        <hr class="divider-custom">
        <div class="row justify-content-center">
          <?php $product_id = $productDetails['product_id']; ?>
          <div class="col  d-flex justify-content-center align-items-center">
            <div id="product-card">
              <div class="d-flex justify-content-center align-items-center product-card">
                <div class="card d-flex flex-column mb-2">
                  <div class="img-container">
                    <img src="api/viewproductImgAPI.php?product_id=<?php echo urlencode($product_id); ?>" class="card-img-top" alt="..."
                      style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  <div class="card-body d-flex justify-content-start">
                    <h5 class="card-title"><i class="bi"></i>
                      <?php echo $productDetails['product_name']; ?></h5>
                    <div class="card-text mt-1">
                      <p>Class : <?php echo $productDetails['product_type']; ?></p>
                      <p>Type : <?php echo $productDetails['type']; ?></p>
                      <p class="fst-italic fw-bold"> <?php echo $productDetails['usage_conditions']; ?></p>
                      <p>
                      <div class="fixed-rating">
                        <?php echo generateStarRating($rating); ?> <?php echo $rating; ?> stars
                      </div>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col rentFormBox">
            <form id="rentForm">
              <div class="mb-3 mx-3">
                <label for="fullname" class="form-label">Full Name <span
                    class="text-danger">*</span></label>
                <input id="fullname" type="text" class="form-control" name="fullname"
                  value="<?php echo $_SESSION['fullname']; ?>">
              </div>
              <div class="mb-3 mx-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input id="email" type="text" class="form-control text-success" name="email"
                  value="<?php echo $_SESSION['email']; ?>" readonly>
              </div>
              <div class="mb-3 mx-3">
                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input id="phone" type="text" class="form-control" name="phone"
                  value="<?php echo $_SESSION['phone']; ?>">
              </div>
              <div class="mb-3 row mx-1">
                <div class="col">
                  <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                  <input id="city" type="text" class="form-control" name="city"
                    value="<?php echo $_SESSION['city']; ?>">
                </div>
                <div class="col">
                  <label for="township" class="form-label">Township <span
                      class="text-danger">*</span></label>
                  <input id="township" type="text" class="form-control" name="township"
                    value="<?php echo $_SESSION['township']; ?>">
                </div>
              </div>
              <div class="mb-3 mx-3">
                <label for="rent_address" class="form-label">Rent Address <span
                    class="text-danger">*</span></label>
                <textarea name="rent_address" id="rent_address" class="form-control"
                  placeholder="Enter Rent Address"></textarea>
              </div>
              <div class="mb-3 mx-3">
                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                <input id="location" type="text" class="form-control" placeholder="Choose location on map"
                  readonly>
                <input type="hidden" name="hiddenLocation" id="hiddenLocation">
                <button type="button" id="chooseLocation" class="btn btn-outline-success mt-2"><i
                    class="bi bi-geo-alt"></i> Google Map</button>
              </div>
              <div class="mb-3 mx-3">
                <label for="start_date" class="form-label">Start Date <span
                    class="text-danger">*</span></label>
                <input id="start_date" type="text" class="form-control" name="start_date"
                  placeholder="Select Start Date">
                <input type="hidden" name="product_id" id="product_id" value="<?php echo $productId; ?>">
                <input type="hidden" name="start_datetime" id="start_datetime">
              </div>
              <div class="mb-3 mx-3">
                <label for="renting_hours" class="form-label">Renting Hours <span class="text-muted">(Estimated)</span> <span
                    class="text-danger">*</span></label>
                <input id="renting_hours" type="number" class="form-control" name="renting_hours" min="8"
                  placeholder="Minimum 8 hrs">


                <input type="hidden" name="end_date" id="end_date">
                <input type="hidden" name="available_date" id="available_date">
              </div>
              <div class="mb-3 mx-3">
                <label for="cost" class="form-label">Cost <span class="text-danger">*</span> <span class="text-muted">(<?php echo $productDetails['cost_per_unit']; ?> Kyats / 8 Hrs)</span></label>
                <input type="hidden" name="hiddenCost" id="hiddenCost"
                  value="<?php echo $productDetails['cost_per_unit']; ?>">
                <input id="cost" type="text" class="form-control text-primary" name="cost" readonly>
                <input type="hidden" name="total_cost" id="total_cost">
              </div>
              <div class="form-check mb-3 mx-3">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                <label class="form-check-label" for="flexCheckDefault">
                  Needed Transportation? (Transportation will be charged separately!) <span
                    class="text-danger">*</span>
                </label>
              </div>

              <div id="pickupFields" class="mb-3 mx-3" style="display:none;">
                <label for="pickup_datetime" class="form-label">Pick Up Date & Time <span
                    class="text-danger">*</span></label>
                <input id="pickup_datetime" type="text" class="form-control" name="pickup_datetime"
                  placeholder="Select Pick Up Date & Time">
              </div>


            </form>
            <div class="gap-2 mt-4 mx-3">
              <button id="submit" type="button" class="btn btn-outline-primary btn-md"><i
                  class="fa-solid fa-arrow-up-from-bracket me-2"></i> Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="mapModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Choose Your Location</h3>
        <div id="map"></div>
        <button type="button" id="saveLocation" class="btn btn-outline-primary btn-md mt-2">Choose Location</button>
      </div>
    </div>

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
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to retrieve saved products count.',
                confirmButtonText: 'OK'
              });
            }
          },
          error: function() {
            console.error('An error occurred while fetching the saved products count.');
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

      window.addEventListener('load', function() {
        // Scroll to the top of the page when it loads
        window.scrollTo(0, 0);
        $('#loadingScreen').hide();
      });

      function goBack() {
        window.history.back();
      }

      let map;
      let marker;
      let chosenLocation;

      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 15,
        });

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition((position) => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            map.setCenter(pos);
            marker = new google.maps.Marker({
              position: pos,
              map: map,
            });
            chosenLocation = pos;
          }, () => {
            handleLocationError(true, map.getCenter());
          });
        } else {
          handleLocationError(false, map.getCenter());
        }

        map.addListener("click", (e) => {
          placeMarkerAndPanTo(e.latLng, map);
        });
      }

      function placeMarkerAndPanTo(latLng, map) {
        if (marker) {
          marker.setPosition(latLng);
        } else {
          marker = new google.maps.Marker({
            position: latLng,
            map: map,
          });
        }
        chosenLocation = latLng;
      }

      document.getElementById("chooseLocation").onclick = function() {
        document.getElementById("mapModal").style.display = "block";
        google.maps.event.trigger(map, 'resize');
        if (chosenLocation) {
          map.panTo(chosenLocation);
        }
      };

      document.getElementById("saveLocation").onclick = function() {
        if (chosenLocation) {
          document.getElementById("location").value = "Location selected";
          document.getElementById("mapModal").style.display = "none";
          document.getElementById("hiddenLocation").value = JSON.stringify(chosenLocation);
          console.log(JSON.stringify(chosenLocation));
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'No Location Selected',
            text: 'Please choose a location on the map.',
            confirmButtonText: 'OK'
          });
        }
      };

      document.getElementsByClassName("close")[0].onclick = function() {
        document.getElementById("mapModal").style.display = "none";
      };

      window.onclick = function(event) {
        if (event.target == document.getElementById("mapModal")) {
          document.getElementById("mapModal").style.display = "none";
        }
      };

      function handleLocationError(browserHasGeolocation, pos) {
        Swal.fire({
          icon: 'error',
          title: 'Geolocation Error',
          text: browserHasGeolocation ?
            'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.',
          confirmButtonText: 'OK'
        });
      }

      document.getElementById('flexCheckDefault').addEventListener('change', function() {
        const pickupFields = document.getElementById('pickupFields');
        if (this.checked) {
          pickupFields.style.display = 'none';
        } else {
          pickupFields.style.display = 'block';
        }
      });

      function validateForm() {
        var product_id = $('#product_id').val();
        var rfullname = $('#fullname').val();
        var remail = $('#email').val();
        var rphone = $('#phone').val();
        var rcity = $('#city').val();
        var rtownship = $('#township').val();
        var rent_address = $('#rent_address').val();
        var hiddenLocation = $('#hiddenLocation').val();
        var start_datetime = $('#start_datetime').val();
        var renting_hours = $('#renting_hours').val();
        var end_date = $('#end_date').val();
        var available_date = $('#available_date').val();
        var total_cost = $('#total_cost').val();

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (
          product_id === '' ||
          rfullname === '' ||
          !emailRegex.test(remail) ||
          rphone === '' ||
          rcity === '' ||
          rtownship === '' ||
          rent_address === '' ||
          hiddenLocation === '' ||
          start_datetime === '' ||
          renting_hours === '' ||
          end_date === '' ||
          available_date === '' ||
          total_cost === ''
        ) {
          Swal.fire({
            icon: 'warning',
            title: 'Incomplete Form',
            text: 'Please fill out all fields with valid data.',
            confirmButtonText: 'OK'
          });
          return false;
        }

        // Check if renting_hours is at least 8
        if (parseInt(renting_hours) < 8) {
          Swal.fire({
            icon: 'warning',
            title: 'Invalid Renting Hours',
            text: 'Renting hours must be at least 8 hours.',
            confirmButtonText: 'OK'
          });
          return false;
        }

        return true;
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

      $(document).ready(function() {
  $('#submit').on('click', function(e) {
    e.preventDefault();
    
    // Show the loading spinner
    $('#loadingScreen').show();

    if (!validateForm()) {
      $('#loadingScreen').hide(); // Hide the spinner if form validation fails
      return;
    }

    var needed_transportation = document.getElementById('flexCheckDefault').checked ? 1 : 0;
    var product_id = $('#product_id').val();
    var rfullname = $('#fullname').val();
    var remail = $('#email').val();
    var rphone = $('#phone').val();
    var rcity = $('#city').val();
    var rtownship = $('#township').val();
    var rent_address = $('#rent_address').val();
    var hiddenLocation = $('#hiddenLocation').val();
    var start_date = $('#start_datetime').val();
    var renting_hours = $('#renting_hours').val();
    var end_date = $('#end_date').val();
    var available_date = $('#available_date').val();
    var total_cost = $('#total_cost').val();
    var need_transportation = needed_transportation;
    var pickup_datetime = $('#pickup_datetime').val();

    $.ajax({
      method: 'POST',
      url: 'api/rentsAPI.php',
      data: {
        product_id: product_id,
        rfullname: rfullname,
        remail: remail,
        rphone: rphone,
        rcity: rcity,
        rtownship: rtownship,
        rent_address: rent_address,
        hiddenLocation: hiddenLocation,
        start_date: start_date,
        renting_hours: renting_hours,
        end_date: end_date,
        available_date: available_date,
        total_cost: total_cost,
        need_transportation: need_transportation,
        pickup_datetime: pickup_datetime
      },
    })
    .done(function(msg) {
      $('#loadingScreen').hide(); // Hide the spinner after processing is complete
      console.log('Response:', msg);
      if (msg.trim().startsWith('Success')) {
        Swal.fire({
          icon: 'success',
          title: 'Form Submitted',
          text: 'Your form has been successfully submitted!',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = './products.php';
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Submission Failed',
          text: 'Something went wrong!',
          confirmButtonText: 'OK'
        });
      }
    })
    .fail(function() {
      $('#loadingScreen').hide(); // Hide the spinner if there's an error
      Swal.fire({
        icon: 'error',
        title: 'Submission Failed',
        text: 'Something went wrong!',
        confirmButtonText: 'OK'
      });
    });
  });
});


      document.addEventListener('DOMContentLoaded', function() {
        const product_idInput = document.getElementById('product_id');
        const startDateInput = document.getElementById('start_date');
        const startDateTimeInput = document.getElementById('start_datetime');
        const endDateInput = document.getElementById('end_date');
        const availableDateInput = document.getElementById('available_date');
        const pickupDateInput = document.getElementById('pickup_date');
        const pickupTimeInput = document.getElementById('pickup_time');
        const rentingHoursInput = document.getElementById('renting_hours');
        const costInput = document.getElementById('cost');
        const totalcostInput = document.getElementById('total_cost');
        const hiddenCostInput = document.getElementById('hiddenCost');
        const costPerDuty = parseFloat(hiddenCostInput.value); // Assuming this value is set from the server
        let isChangingDate = false;

        function getMinDate() {
          const now = new Date();
          const minDate = new Date(now);
          if (now.getHours() >= 8) {
            minDate.setDate(minDate.getDate() + 1);
          }
          return minDate;
        }

        // Initialize Flatpickr for the start date input without time picker
        flatpickr(startDateInput, {
          dateFormat: "Y-m-d",
          minDate: getMinDate(),
          onChange: function(selectedDates, dateStr, instance) {
            if (isChangingDate) return;
            isChangingDate = true;

            const selectedDate = new Date(selectedDates[0]);
            const minDate = getMinDate();

            if (selectedDate < minDate) {
              instance.setDate(minDate, true);
            }

            isChangingDate = false;
          }
        });

        flatpickr("#pickup_date", {
          dateFormat: "Y-m-d"
        });

        flatpickr("#pickup_time", {
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
        });

        // Calculate cost based on renting hours
        // Initialize Flatpickr for pickup date and time in one input
        const pickupDatetimeInput = document.querySelector("#pickup_datetime");

        function calculateCostAndEndDate() {
          const start_date = startDateInput.value;
          const product_id = product_idInput.value;
          const renting_hours = parseInt(rentingHoursInput.value);

          $.ajax({
            url: 'api/calculateEndDateAPI.php',
            type: 'POST',
            data: {
              start_date: start_date,
              renting_hours: renting_hours,
              cost_per_duty: costPerDuty,
              product_id: product_id
            },
            success: function(response) {
              const result = JSON.parse(response);
              costInput.value = result.cost + " Kyats";
              startDateTimeInput.value = result.start_datetime;
              endDateInput.value = result.end_date;
              availableDateInput.value = result.available_date;
              totalcostInput.value = result.cost;
              const available_datetime = result.available_datetime;
              const currentDateTime = new Date();
              console.log(result.start_datetime);
              console.log(result.end_date);
              console.log(result.available_date);

              // Set constraints on pickup date and time
              const flatpickrOptions = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: currentDateTime,
                maxDate: result.beforeStart_datetime,
                time_24hr: false,
              };

              // Only set minDate if available_datetime is not null
              if (available_datetime) {
                flatpickrOptions.minDate = available_datetime;
              }

              flatpickr(pickupDatetimeInput, flatpickrOptions);
            }
          });
        }

        rentingHoursInput.addEventListener('input', calculateCostAndEndDate);
        startDateInput.addEventListener('change', calculateCostAndEndDate);

        // Set initial cost calculation
        calculateCostAndEndDate();
      });
    </script>
    <?php include 'logout-link.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
  </body>

  </html>
<?php
} else {
  header("Location: signin.php");
}
