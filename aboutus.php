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

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MKH Rental_About Us</title>
  <?php if (isset($_SESSION['user_id'])) { ?>
<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('dmsk7dnwwns5');
</script>
<?php } ?>
<!-- End of Async Drift Code -->
  <link rel="icon" type="image/png" href="images/small_logo.png">
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
  <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/aboutus.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
 .accessibility-circle {
    position: fixed;
    bottom: 33px;
    left: 120px;
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
    bottom: 88px;
    left : 125px;
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
  <!--NAVBAR-->
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
            <a class="nav-link" href="home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="products.php">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="aboutus.php">About Us</a>
          </li>
        </ul>

        <form class="d-flex" id="searchForm" action="products.php" method="GET" style="margin-top:auto; margin-bottom: auto;">
          <div class="input-group mx-2">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput" name="query" />
            <button id="searchrecipe" class="btn btn-outline-secondary" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </nav>

  <div class="mx-4">
    <nav aria-label="breadcrumb" class="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="aboutus.php">About us</a></li>
      </ol>
    </nav>
  </div>
  <section class="py-2 py-md-5 py-xl-8 aboutus" style="margin-bottom:80px;">
    <div class="container">
      <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6 col-xl-5">
          <img class="img-fluid rounded" loading="lazy" src="images/about-img.png" alt="">
        </div>
        <div class="col-12 col-lg-6 col-xl-7">
          <div class="row justify-content-xl-center">
            <div class="col-12 col-xl-11">
              <h2 class="h1 mb-3">MKH Rental</h2>
              <p class="lead fs-4 text-secondary mb-3">Welcome to MKH Rental, your trusted partner in excavator rentals in Myanmar. With years of experience in the construction and heavy equipment industry, we understand the unique needs of our clients, whether they are undertaking large-scale commercial projects or smaller residential tasks.</p>
              <!-- <p class="mb-5">At MKH Rental, our mission is to provide reliable, high-quality excavators that meet the diverse needs of our customers. We are committed to ensuring that our equipment helps you get the job done efficiently, safely, and on time.</p> -->
              <div class="row gy-4 gy-md-0 gx-xxl-5X">
                <div class="col-12 col-md-6">
                  <div class="d-flex">
                    <div class="me-4 text-primary">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                      </svg>
                    </div>
                    <div>
                      <h4 class="mb-3">Quality Equipments</h4>
                      <p class="text-secondary mb-0">Well-maintained excavators with the latest technology.</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="d-flex">
                    <div class="me-4 text-primary">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                        <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z" />
                      </svg>
                    </div>
                    <div>
                      <h4 class="mb-3">Expert Support</h4>
                      <p class="text-secondary mb-0">Knowledgeable staff to help you choose the right machine.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
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
                <a href="signout.php" class="text-reset">Sign Out</a>
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
  </script>
  <?php include 'logout-link.php'?>
</body>

</html>