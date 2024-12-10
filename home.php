<?php session_start();
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
  <title>MKH Rental_Home Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="icon" type="image/png" href="images/small_logo.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
  <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/home.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
/* Accessibility Icon */
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
            <a class="nav-link active" aria-current="page" href="home.php">Home</a>
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


  <section id="intro">
    <div class="search-container">
      <form class="d-flex d-lg-none" id="searchForm" action="products.php" method="GET">
        <div class="input-group">
          <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput" name="query" />
          <button id="searchproduct" class="btn btn-outline-secondary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>
    </div>
    <div class="container text-dark custom-container">
      <div class="row text-center mb-4" style="margin-top: 30px;">
        <h2 class="fs-1 fw-bold">Our Objectives</h2>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-4 col-sm-4 text-center">
          <h3>For Reliable</h3>
          <p class="d-none d-md-block">
            We ensure that all our excavators are well-maintained and ready for use, minimizing downtime and maximizing productivity on your projects.
          </p>
          <hr class="divider-custom d-block d-md-none">
        </div>
        <div class="col-md-4 col-sm-4 text-center">
          <h3>For Affordable</h3>
          <p class="d-none d-md-block">
            Our rental rates are competitive, with flexible pricing options to suit different project budgets. You get the best value without compromising on quality.
          </p>
          <hr class="divider-custom d-block d-md-none">
        </div>
        <div class="col-md-4 col-sm-4 text-center">
          <h3>For Convenient</h3>
          <p class="d-none d-md-block">
            Our platform provides a seamless rental experience with easy online booking, real-time availability, and prompt delivery services to your project site.
          </p>
          <hr class="divider-custom d-block d-md-none">
        </div>
      </div>
    </div>

  </section>

  <!-- Most Favourite Recipes -->
  <!-- Show the carousel only on Larger screens -->
  <section id="mostRent" class="container mt-2">
    <h2 class="mb-4 fs-1 fw-bold">Most Rented Products</h2>
    <div class="d-block d-md-block d-lg-block">
      <div class="row" id="mostRentdisplay"></div>
    </div>
  </section>

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
//     window.addEventListener('load', function() {
//     var observer = new MutationObserver(function(mutations) {
//         mutations.forEach(function(mutation) {
//             mutation.addedNodes.forEach(function(node) {
//                 if (node.nodeType === 1 && node.matches('div[style*="coze-z-index-iframe"]')) { // Checking if it's an element node and matches the condition
//                     node.style.height = '37rem'; // example
//                 }
//             });
//         });
//     });

//     observer.observe(document.body, { childList: true, subtree: true });
// });


// document.addEventListener("DOMContentLoaded", function () {
//     var zoomLevel = 1;

//     document.getElementById("zoom-in").addEventListener("click", function () {
//         zoomLevel += 0.1;
//         document.body.style.transform = "scale(" + zoomLevel + ")";
//         document.body.style.transformOrigin = "0 0";
//     });

//     document.getElementById("zoom-out").addEventListener("click", function () {
//         zoomLevel -= 0.1;
//         if (zoomLevel < 0.5) zoomLevel = 0.5; // Prevent zooming out too much
//         document.body.style.transform = "scale(" + zoomLevel + ")";
//         document.body.style.transformOrigin = "0 0";
//     });
// });

// document.addEventListener("keydown", function (event) {
//     if (event.key === "+") {
//         document.getElementById("zoom-in").click();
//     } else if (event.key === "-") {
//         document.getElementById("zoom-out").click();
//     }
// });


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


    function mostRentedProducts() {
      $.ajax({
        url: 'api/mostRentedAPI.php',
        type: 'POST',
        data: {},
        dataType: 'json',
        success: function(data) {
          console.log(data);
          window.scrollTo(0, 0);
          displayMostRented(data);
        },
        error: function(error) {
          console.log('Error during search:', error);
        }
      });
    }


    function displayMostRented(data) {
      var displayResultsContainer = $("#mostRentdisplay");
      displayResultsContainer.empty();

      var resultProductsSection = $('<div class="d-block d-md-block d-lg-block"></div>');
      var resultProductsRow = $('<div class="row"></div>');

      $.each(data, function(index, product) {
        var productId = product.product_id;
        var isSaved = product.is_saved; // Assuming the API returns whether the product is saved or not
        var heartIconClass = isSaved ? 'bi-bookmark-fill' : 'bi-bookmark';
        var activeIconClass = product.is_active ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger';
        var rentButton = product.is_active ? '<a href="#" class="btn btn-success btn-md card-link" id="rentbutton">Rent</a>' : '<button class="btn btn-secondary btn-md card-link" disabled>Rent</button>';
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
          '               <hr class="pdivider-custom">' +
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
          '               <a href="#" class="btn btn-primary btn-md card-link" id="morebutton">More</a>' +
          '           </div>' +
          '       </div>' +
          '   </div>' +
          '</div>'
        );


        resultProductCard.find('#morebutton').on('click', function() {
          redirectToProductDetails(productId);
        });

        resultProductCard.find('#rentbutton').on('click', function() {
          redirectToRentForm(productId);
        });

        resultProductsRow.append(resultProductCard);
      });

      resultProductsSection.append(resultProductsRow);
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


    mostRentedProducts();
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
          alert("Failed to load!");
        }
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
    });
  </script>
  <?php include "logout-link.php"?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>