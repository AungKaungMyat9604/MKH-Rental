<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
  $user_id = $_SESSION['user_id'];
  $fullnameParts = explode(" ", $_SESSION['fullname']);
  $firstName = $fullnameParts[0];
} else {
  $user_id = '';
}

$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($productId === null) {
  die("Product ID is not set in the URL.");
}

function fetchProductDetailsFromDatabase($productId)
{
  require 'api/dbinfo.php';

  $sql = "SELECT product_name,
            product_type,
            type,
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
function isProductSaved($userId, $productId)
{
  require 'api/dbinfo.php';
  $sql = "SELECT * FROM saved_products WHERE user_id = $userId AND product_id = $productId";
  $result = $conn->query($sql);
  $conn->close();
  return $result && $result->num_rows > 0;
}

$isSaved = isset($_SESSION['user_id']) ? isProductSaved($_SESSION['user_id'], $productId) : false;
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<head>
  <title>MKH Rental_Product Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="icon" type="image/png" href="images/small_logo.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
  <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/productDetails.css">
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
        <img src="images/Logo.png" class="img-fluid" alt="MKH Group"
          style="width: auto; height: 55px; margin-left: 5px; border-radius: 15px;" />
      </a>

      <div class="d-flex align-items-center order-lg-2">
        <?php if (isset($_SESSION['email'])) { ?>
          <li class="nav-item">
            <a class="ms-4" href="savedList.php">
              <i class="fa-solid fa-bookmark text-warning fs-3"></i>
              <span class="badge bg-primary badge-number" style="margin-left:-8px;">0</span>
              <!-- The badge will be updated dynamically -->
            </a>
          </li>
          <div class="nav-item dropdown d-flex align-items-center ms-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <span class="d-none d-md-block me-3 text-white">
                <i class="fa-solid fa-caret-down p-1"></i><?php echo $firstName; ?>
              </span>
              <span class="d-block d-md-none me-2 text-white">
                <i class="fa-solid fa-caret-down"></i>
              </span>
              <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Profile"
                class="rounded-circle img-fluid"
                style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
              <li><a class="dropdown-item text-muted text-center"><?php echo $_SESSION['fullname']; ?></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="profilesetting.php"><i
                    class="fa-regular fa-address-card me-2"></i>Profile Settings</a></li>
              <?php if (isset($_SESSION['email'])) { ?>
                <li><a href="savedList.php" class="dropdown-item" id="savedDropdown"><i
                      class="fa-solid fa-bookmark me-2"></i> Saved List</a></li>
                <li><a class="dropdown-item" href="renthistory.php"><i
                      class="fa-solid fa-clock-rotate-left me-2"></i></i>Rent History</a></li>
              <?php } ?>
              <li>
                <hr class="dropdown-divider">
              </li>

              <li><a class="dropdown-item" href="#" id="logout-link"><i class="fa-solid fa-right-from-bracket me-2"></i>Log
                  Out</a></li>
            </ul>
          </div>
        <?php } else { ?>
          <div class="nav-item dropdown d-flex align-items-center ms-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <span class="d-none d-md-block me-3 text-white">
                <i class="fa-solid fa-caret-down p-1"></i>Log In
              </span>
              <span class="d-block d-md-none me-2 text-white">
                <i class="fa-solid fa-caret-down"></i>
              </span>
              <img src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Profile"
                class="rounded-circle img-fluid"
                style="background-color: white; width: 50px; height: 50px; object-fit: cover;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
              <li><a class="dropdown-item" href="signin.php"><i class="fa-solid fa-right-to-bracket me-2"></i>Log In</a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="signup.php"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>Sign
                  Up</a></li>
            </ul>
          </div>
        <?php } ?>

        <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
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

        <form class="d-flex d-none d-lg-block" id="searchForm" action="products.php" method="GET"
          style="margin-top:auto; margin-bottom: auto;">
          <div class="input-group mx-2">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput"
              name="query" />
            <button id="searchproduct" class="btn btn-outline-secondary" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </nav>
  <?php $product_id = $productDetails['product_id']; ?>
  <div id="main" class="main">
    <div class="container1111">
      <nav aria-label="breadcrumb" class="breadcrumb ps-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          <li class="breadcrumb-item"><a href="products.php">Products</a></li>
          <li class="breadcrumb-item active" aria-current="page"><a href="#">Product Details</a></li>
        </ol>
      </nav>
      <div class="container d-flex align-items-start p-0 mx-4">
        <button id="backButton" class="btn btn-secondary" style="position:fixed; z-index:9999999;" onclick="goBack()">
          <i class="bi bi-arrow-left"></i> Back
        </button>
      </div>
      <div class="detail">
        <div class="image">
          <img src="api/viewproductImgAPI.php?product_id=<?php echo urlencode($product_id); ?>"
            alt="<?php echo $productDetails['product_name']; ?>">
        </div>
        <div class="content">
          <h1 class="name">
            <i
              class="bi <?php echo $productDetails['is_active'] ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger'; ?>"></i>
            <?php echo htmlspecialchars($productDetails['product_name']); ?>
          </h1>

          <h1 class="product_type"><?php echo $productDetails['product_type']; ?></h1>
          <div class="price">Ks<?php echo $productDetails['cost_per_unit']; ?> / Duty</div>
          <div class="buttons d-flex justify-content-start align-items-center px-5 py-3">
            <button class="btn btn-lg"
              data-userid="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"
              onclick="toggleSave(<?php echo $productDetails['product_id']; ?>, this)">
              <i class="text-warning bi fs-3 <?php echo $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark'; ?>"></i>
            </button>
            <?php echo $productDetails['is_active'] ? '<a href="#"
              class="btn btn-success btn-lg card-link rentbutton">Rent Now</a>' : '<button class="btn btn-secondary btn-md card-link viewinfo"><i class="bi bi-info-circle"></i></button>'; ?>

          </div>
          <div class="description custom-container px-5" style="width: 85%; margin: auto;">
            <h3>Product Details</h3>
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($productDetails['brand']); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($productDetails['type']); ?></p>
            <?php if ($productDetails['product_type'] == 'Excavator') { ?>
              <p><strong>Ton:</strong> <?php echo htmlspecialchars($productDetails['ton']); ?></p>
              <p><strong>Arm Length:</strong> <?php echo htmlspecialchars($productDetails['arm_length']); ?></p>
            <?php } ?>
            <?php if ($productDetails['type'] == 'Bucket' || $productDetails['product_type'] == 'Excavator') { ?>
              <p><strong>Bucket Size:</strong> <?php echo htmlspecialchars($productDetails['bucket_size']); ?></p>
            <?php } ?>
            <?php if ($productDetails['product_type'] != 'Attachment') { ?>
              <p><strong>Dimension:</strong> <?php echo htmlspecialchars($productDetails['dimension']); ?></p>
              <p><strong>Fuel Tank Capacity:</strong>
                <?php echo htmlspecialchars($productDetails['fuel_tank_capacity']); ?></p>
            <?php } ?>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($productDetails['weight']); ?></p>
            <?php if ($productDetails['product_type'] != 'Excavator') {
              if ($productDetails['type'] != 'Breaker') { ?>
                <p><strong>Capacity Size:</strong> <?php echo htmlspecialchars($productDetails['capacity_size']); ?></p>
              <?php } ?>
              <p><strong>Compatible Excavator:</strong>
                <?php echo htmlspecialchars($productDetails['compatible_excavator']); ?></p>
            <?php } ?>
            <p><strong>Usage Conditions:</strong> <?php echo htmlspecialchars($productDetails['usage_conditions']); ?>
            </p>
            <!-- <p><strong>Status:</strong> <?php echo $productDetails['is_active'] ? 'Active' : 'Busy'; ?></p> -->
          </div>
        </div>
      </div>
    </div>

    <div class="container mb-5 d-flex justify-content-center" style="max-width: 100%; padding:10px;">
      <div class="row" style="width: 100%;">
        <div class="col-sm-6 text-center m-auto">
          <div class="container ">
            <div class="row">
              <div class="container mb-2">
                <h1><span id="avg_rating">0.0</span>/5.0</h1>
                <div>
                  <i class="fa fa-star star-light main_star mr-1"></i>
                  <i class="fa fa-star star-light main_star mr-1"></i>
                  <i class="fa fa-star star-light main_star mr-1"></i>
                  <i class="fa fa-star star-light main_star mr-1"></i>
                  <i class="fa fa-star star-light main_star mr-1"></i>
                </div>
                <a><span id="total_review">0</span> Reviews </a>
              </div>
              <div class="row progressSection">
                <div class="col px-5">
                  <div class='holder' data-star="5">
                    <div>
                      <div class="progress-label-left">
                        <b>5</b> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
                      </div>
                      <div class="progress-label-right">
                        <span id="total_five_star_review">0</span> Reviews
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" id='five_star_progress'></div>
                    </div>
                  </div>

                  <div class='holder' data-star="4">
                    <div>
                      <div class="progress-label-left">
                        <b>4</b> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i>
                      </div>
                      <div class="progress-label-right">
                        <span id="total_four_star_review">0</span> Reviews
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" id='four_star_progress'></div>
                    </div>
                  </div>

                  <div class='holder' data-star="3">
                    <div>
                      <div class="progress-label-left">
                        <b>3</b> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
                      </div>
                      <div class="progress-label-right">
                        <span id="total_three_star_review">0</span> Reviews
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" id='three_star_progress'></div>
                    </div>
                  </div>

                  <div class='holder' data-star="2">
                    <div>
                      <div class="progress-label-left">
                        <b>2</b> <i class="fa fa-star mr-1 text-warning"></i> <i
                          class="fa fa-star mr-1 text-warning"></i>
                      </div>
                      <div class="progress-label-right">
                        <span id="total_two_star_review">0</span> Reviews
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" id='two_star_progress'></div>
                    </div>
                  </div>

                  <div class='holder' data-star="1">
                    <div>
                      <div class="progress-label-left">
                        <b>1</b> <i class="fa fa-star mr-1 text-warning"></i>
                      </div>
                      <div class="progress-label-right">
                        <span id="total_one_star_review">0</span> Reviews
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" id='one_star_progress'></div>
                    </div>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="container custom-container">
            <button id="escape_filter" style="display:none;" class="btn"><i class="fa-solid fa-xmark"></i></button>
            <div id="display_review">
              <div class="d-flex justify-content-center align-items-center" style="height:100%;">
                No Comments.
              </div>
            </div>
            <div class="text-center mt-3">
              <button class="btn btn-primary" id="add_review">Add Review</button>
            </div>
          </div>
        </div>
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


      <!-- The Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Write your Review</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <!-- <span aria-hidden="true">&times;</span> -->
              </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body text-center">
              <div class="rating">
                <i class="fa fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                <i class="fa fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                <i class="fa fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                <i class="fa fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                <i class="fa fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
              </div>
              <div class="form-group mt-2">
                <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter Name" <?php if (isset($_SESSION['email'])) { ?> value="<?php echo $_SESSION['fullname']; ?>" <?php } ?>>
              </div>
              <div class="form-group mt-2">
                <textarea name="message" id="message" class="form-control" placeholder="Enter message"></textarea>
              </div>
              <div class="form-group mt-2">
                <button class="btn btn-primary" id="sendReview">Submit</button>
              </div>
            </div>
          </div>
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

      window.addEventListener('load', function() {
        // Scroll to the top of the page when it loads
        window.scrollTo(0, 0);
      });

      function goBack() {
        window.history.back();
      }

      $(document).ready(function() {
        $(document).on('click', '.rentbutton', function() {
          var productId = <?php echo $productDetails['product_id']; ?>;
          redirectToRentForm(productId);
        });

        $(document).on('click', '.viewinfo', function() {
          var productId = <?php echo $productDetails['product_id']; ?>;
          console.log('Clicked');
          ViewRentInformation(productId);

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
          }
        });

        function redirectToRentForm(productId) {
          window.location.href = 'rentForm.php?product_id=' + productId;
        }

        const displayReview = document.getElementById('display_review');
        $(document).ready(function() {
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
                }).then(() => {
                  if (response.success) {
                    var heartIcon = $(button).find('i');
                    heartIcon.removeClass('bi-bookmark bi-bookmark-fill');
                    heartIcon.addClass(response.isSaved ? 'bi-bookmark-fill' : 'bi-bookmark');
                    location.reload(); // Reload the page to update content
                    updateSavedProductsCount();
                  }
                });
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
              title: 'Sign in required',
              text: "Please Sign in to Save.",
              showCancelButton: true,
              confirmButtonText: 'Log In',
              cancelButtonText: 'Cancel'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = 'signin.php'; // Redirect to the login page
              }
            });
          }
        };

        var rating_value = 0;

        $('#add_review').click(function() {
          <?php if (isset($_SESSION['user_id'])) { ?>
            $('#myModal').modal('show');
          <?php } else { ?>
            Swal.fire({
              icon: 'info',
              title: 'Sign in required',
              text: "Please Sign in to add review.",
              showCancelButton: true,
              confirmButtonText: 'Log In',
              cancelButtonText: 'Cancel'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = 'signin.php'; // Redirect to the login page
              }
            });
          <?php } ?>
        });

        function resetStar() {
          for (var i = 1; i <= 5; i++) {
            $('#submit_star_' + i).removeClass('text-warning'); // Remove the highlight
            $('#submit_star_' + i).addClass('star-light'); // Add the default light color
          }
        }

        $(document).on('click', '.submit_star', function() {
          var rating_value = $(this).data('rating'); // Get the rating from the clicked star
          resetStar(); // Reset all the stars first

          for (var i = 1; i <= rating_value; i++) {
            $('#submit_star_' + i).removeClass('star-light');
            $('#submit_star_' + i).addClass('text-warning'); // Add the highlight class
            console.log('Star clicked: ' + i); // Log the clicked star

          }
          console.log('Hello' + rating_value);


          $('#sendReview').click(function() {
            var user_id = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
            var product_id = <?php echo isset($_GET['product_id']) ? json_encode($_GET['product_id']) : 'null'; ?>;
            var message = $('#message').val();
            var rating = rating_value; // Update this line to get rating from rating_value

            if (!user_id || !message) {
              Swal.fire({
                icon: 'warning',
                title: 'Incomplete',
                text: 'Please fill both fields.',
                confirmButtonText: 'OK'
              });
              return false;
            }

            $.ajax({
              url: 'api/postreviewAPI.php',
              method: 'POST',
              data: {
                rating: rating,
                user_id: user_id,
                product_id: product_id,
                message: message
              },
              success: function(data) {
                $('#myModal').modal('hide');
                displayReview.scrollTop = 0;
                console.log(data);
                loadData();

                Swal.fire({
                  icon: 'success',
                  title: 'Review Submitted',
                  text: 'Your review has been successfully submitted!',
                  confirmButtonText: 'OK'
                });
              },
              error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'An error occurred. Please try again.',
                  confirmButtonText: 'OK'
                });
              }
            });

          });
        });

        function loadData() {
          var product_id = <?php echo isset($_GET['product_id']) ? json_encode($_GET['product_id']) : 'null'; ?>;
          $.ajax({
            url: 'api/postreviewAPI.php',
            method: "POST",
            data: {
              action: 'load_data',
              product_id: product_id
            },
            success: function(data) {
              var parsedData = JSON.parse(data);

              console.log(parsedData);
              $('#avg_rating').text(parsedData.avgUserRatings);
              $('#total_review').text(parsedData.totalReviews);
              $('#total_five_star_review').text(parsedData.totalRatings5);
              $('#total_four_star_review').text(parsedData.totalRatings4);
              $('#total_three_star_review').text(parsedData.totalRatings3);
              $('#total_two_star_review').text(parsedData.totalRatings2);
              $('#total_one_star_review').text(parsedData.totalRatings1);

              $('#five_star_progress').css('width', (parsedData.totalRatings5 / parsedData.totalReviews) * 100 + '%');
              $('#four_star_progress').css('width', (parsedData.totalRatings4 / parsedData.totalReviews) * 100 + '%');
              $('#three_star_progress').css('width', (parsedData.totalRatings3 / parsedData.totalReviews) * 100 + '%');
              $('#two_star_progress').css('width', (parsedData.totalRatings2 / parsedData.totalReviews) * 100 + '%');
              $('#one_star_progress').css('width', (parsedData.totalRatings1 / parsedData.totalReviews) * 100 + '%');

              var avgRating = parsedData.avgUserRatings;
              var countStar = 0;
              $('.main_star').each(function() {
                countStar++;
                if (avgRating >= countStar) {
                  $(this).addClass('text-warning');
                  $(this).removeClass('star-light');
                } else if (avgRating >= (countStar - 0.5)) {
                  $(this).addClass('fa-star-half-alt text-warning');
                  $(this).removeClass('fa-star star-light');
                }
              });

              if (parsedData.ratingsList.length > 0) {
                var html = '';
                for (var count = 0; count < parsedData.ratingsList.length; count++) {
                  html += `<div class='row mt-2'>`;
                  html += `<div class='col-3 col-md-3 col-lg-2 d-flex justify-content-center align-items-center'>`;
                  const user_id = parsedData.ratingsList[count].user_id || '';
                  html += `<img src="api/viewprofilepictureAPI.php?user_id=${encodeURIComponent(user_id)}" alt="Profile" class="rounded-circle img-fluid" style="background-color: white; width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">`;
                  html += `</div>`;
                  html += `<div class='col-9 col-md-9 col-lg-10'>`;
                  html += `<div class='card'>`;
                  html += `<div class='card-header'>${parsedData.ratingsList[count].fullname}</div>`;
                  html += `<div class='card-body'>`;
                  for (var star = 0; star < 5; star++) {
                    var className = (parsedData.ratingsList[count].rating > star) ? 'text-warning' : 'star-light';
                    html += `<i class="fa fa-star mr-1 ${className}"></i>`;
                  }
                  html += `<br>${parsedData.ratingsList[count].message}`;
                  html += `</div>`;
                  html += `<div class='card-footer'>${parsedData.ratingsList[count].datetime}</div>`;
                  html += `</div>`;
                  html += `</div>`;
                  html += `</div>`;
                }
                $('#display_review').html(html);
              }

            } // success
          });
        }

        loadData();

        $('.holder').click(function() {
          var starValue = $(this).find('.progress-label-left b').text();
          filterCommentsByStar(starValue);
          document.getElementById('display_review').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
        });

        $('#escape_filter').click(function() {
          $(this).hide();
          loadData(); // Load all comments
        });

        function filterCommentsByStar(star) {
          var product_id = <?php echo isset($_GET['product_id']) ? json_encode($_GET['product_id']) : 'null'; ?>;
          $.ajax({
            url: 'api/viewreviewAPI.php',
            method: "POST",
            data: {
              action: 'filter_comments',
              product_id: product_id,
              star: star
            },
            success: function(data) {
              var parsedData = JSON.parse(data);
              displayFilteredComments(parsedData.ratingsList);
              $('#escape_filter').show(); // Show escape button
            }
          });
        }

        function displayFilteredComments(comments) {
          if (comments.length > 0) {
            var html = '';
            for (var count = 0; count < comments.length; count++) {
              html += `<div class='row mt-2'>`;
              html += `<div class='col-3 col-md-3 col-lg-2 d-flex justify-content-center align-items-center'>`;
              const user_id = comments[count].user_id || '';
              html += `<img src="api/viewprofilepictureAPI.php?user_id=${encodeURIComponent(user_id)}" alt="Profile" class="rounded-circle img-fluid" style="background-color: white; width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">`;
              html += `</div>`;
              html += `<div class='col-9 col-md-9 col-lg-10'>`;
              html += `<div class='card'>`;
              html += `<div class='card-header'>${comments[count].fullname}</div>`;
              html += `<div class='card-body'>`;
              for (var star = 0; star < 5; star++) {
                var className = (comments[count].rating > star) ? 'text-warning' : 'star-light';
                html += `<i class="fa fa-star mr-1 ${className}"></i>`;
              }
              html += `<br>${comments[count].message}`;
              html += `</div>`;
              html += `<div class='card-footer'>${comments[count].datetime}</div>`;
              html += `</div>`;
              html += `</div>`;
              html += `</div>`;
            }
            $('#display_review').html(html);
          }
        }
      });
    </script>
    <?php include 'logout-link.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"></script>
</body>

</html>