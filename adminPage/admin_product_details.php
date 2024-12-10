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
// function isProductSaved($userId, $productId)
// {
//   require 'api/dbinfo.php';
//   $sql = "SELECT * FROM saved_products WHERE user_id = $userId AND product_id = $productId";
//   $result = $conn->query($sql);
//   $conn->close();
//   return $result && $result->num_rows > 0;
// }

// $isSaved = isset($_SESSION['user_id']) ? isProductSaved($_SESSION['user_id'], $productId) : false;
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<head>
  <title>MKH Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">
  <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/product_details.css">
  <style>
    h3,
    h2 {
      font-family: Agbalumo;
    }

    section {
      padding: 50px;
    }

    .main {
      width: 100%;
      Height: 100%;
      margin: 0;
    }

    body {
      background-color: rgb(179, 185, 189);
      width: 100%;
      height: 100%;
      margin: 0;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
      /* Light gray background */
      color: #007bff;
      /* Bootstrap primary color for text */
    }

    a {
      color: unset;
      text-decoration: none;
    }

    .container1111 {
      width: 100%;
      text-align: center;
      margin-top: 80px;
      padding-top: 10px;
      padding: 10px;
    }

    .custom-container {
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(7px);
      border-radius: 15px;
      margin-top: 20px;
      padding: 20px;
      width: 100%;
      /* Full width for the container */
    }


    .title {
      font-size: xx-large;
      padding: 20px 0;
    }

    .listProduct .item img {
      width: 90%;
      filter: drop-shadow(0 50px 20px #0009);
    }

    .listProduct {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }

    .listProduct .item {
      background-color: #EEEEE6;
      padding: 20px;
      border-radius: 20px;
    }

    .listProduct .item h2 {
      font-weight: 500;
      font-size: large;
    }

    .listProduct .item .price {
      letter-spacing: 3px;
      font-size: small;
    }

    /* Detail page */
    .detail {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 50px;
      text-align: left;
    }

    .detail .image img {
      width: 100%;
    }

    .detail .image {
      position: relative;
    }

    .detail .image::before {
      position: absolute;
      width: 300px;
      height: 300px;
      content: '';
      background-color: #94817733;
      z-index: -1;
      border-radius: 190px 100px 170px 180px;
      left: calc(50% - 150px);
      top: 50px;
    }

    .detail .name {
      font-size: xxx-large;
      padding: 40px 0 0 0;
      margin: 0 0 10px 0;
    }

    .detail .product_type {
      font-size: large;
      color: grey;
    }

    .detail .price {
      font-weight: bold;
      font-size: x-large;
      letter-spacing: 5px;
      margin-bottom: 0px;
    }

    .detail .description {
      font-weight: 300;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .detail .description p {
      margin: 10px 0;
    }

    .detail .description h3 {
      margin-bottom: 20px;
    }

    .progressSection .holder {
      display: flex;
      flex-direction: column;
      margin-bottom: 1em;
    }

    .progressSection .holder>div {
      display: flex;
      justify-content: space-between;
    }



    .breadcrumb-item a {
      text-decoration: underline;
      color: rgb(33, 37, 41);
      font-size: large;
      font-weight: 600;
    }

    .star-light {
      color: #999 !important
    }


    .submit_star {
      cursor: pointer;
    }

    /* iPad */
    @media only screen and (max-width: 992px) {
      .listProduct {
        grid-template-columns: repeat(3, 1fr);
      }

      .detail {
        grid-template-columns: 40% 1fr;
      }
    }

    /* Mobile */
    /* Mobile */
    @media only screen and (max-width: 768px) {
      .listProduct {
        grid-template-columns: repeat(2, 1fr);
      }

      .detail {
        text-align: center;
        grid-template-columns: 1fr;
        width: 100%;
        /* Ensure full width on mobile */
      }

      .detail .image img {
        width: 100%;
        /* Ensure image fills the width */
        height: auto;
        /* Maintain aspect ratio */
      }

      .detail .name {
        font-size: x-large;
        margin: 0;
      }

      .detail .buttons button {
        font-size: small;
      }

      .detail .buttons {
        justify-content: center;
      }

      .detail .description {
        text-align: left;
      }
    }


    #display_review {
      max-height: 400px;
      overflow-x: hidden;
      width: 100%;
      overflow-y: auto;
      height: 400px;
    }

    .holder {
      cursor: pointer;
    }
    
  </style>
  <?php include 'admin_style.php' ?>
</head>

<body>
  <!--NAVBAR-->
 <?php include 'admin_navbar.php'; ?>

  <div id="main" class="main">
    <div class="container1111">
      <div class="container d-flex align-items-start p-0 mx-4">
        <button id="backButton" class="btn btn-secondary" style="position:fixed; z-index:999999;"  onclick="goBack()">
          <i class="bi bi-arrow-left"></i> Back
        </button>
      </div>
      <?php $product_id = $productDetails['product_id']; ?>
      <div class="detail">
        <div class="image">
          <img src="api/viewproductImgAPI.php?product_id=<?php echo urlencode($product_id); ?>" alt="<?php echo $productDetails['product_name']; ?>">
        </div>
        <div class="content">
          <h1 class="name">
            <i class="bi <?php echo $productDetails['is_active'] ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger'; ?>"></i>
            <?php echo htmlspecialchars($productDetails['product_name']); ?>
          </h1>

          <h1 class="product_type"><?php echo $productDetails['product_type']; ?></h1>
          <div class="price">Ks<?php echo $productDetails['cost_per_unit']; ?> / Duty</div>
          <div class="buttons d-flex justify-content-start align-items-center px-5 py-3">
            <a class="btn btn-primary btn-md card-link" id="editbutton" onclick="editProductDetails(<?php echo $product_id; ?>)">Edit Details</a>
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
              <p><strong>Fuel Tank Capacity:</strong> <?php echo htmlspecialchars($productDetails['fuel_tank_capacity']); ?></p>
            <?php } ?>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($productDetails['weight']); ?></p>
            <?php if ($productDetails['product_type'] != 'Excavator') {
              if ($productDetails['type'] != 'Breaker') { ?>
                <p><strong>Capacity Size:</strong> <?php echo htmlspecialchars($productDetails['capacity_size']); ?></p>
              <?php } ?>
              <p><strong>Compatible Excavator:</strong> <?php echo htmlspecialchars($productDetails['compatible_excavator']); ?></p>
            <?php } ?>
            <p><strong>Usage Conditions:</strong> <?php echo htmlspecialchars($productDetails['usage_conditions']); ?></p>
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
                        <b>5</b> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
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
                        <b>4</b> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
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
                        <b>3</b> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
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
                        <b>2</b> <i class="fa fa-star mr-1 text-warning"></i> <i class="fa fa-star mr-1 text-warning"></i>
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

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" enctype="multipart/form-data">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="img-container mb-1 d-flex justify-content-start">
                            <img id="product_image_preview" class="card-img-top" alt="Product Image" style="width: auto; height: 245px; object-fit: cover; border: 1px solid black;">
                        </div>
                        <a href="#" class="btn btn-primary" id="uploadpic" onclick="triggerFileInput()">Upload</a>
                        <input type="file" name="file" id="file" style="display: none;" onchange="handleFileInputChange()" required>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="product_type" class="form-label">Product Type</label>
                            <input type="text" class="form-control" id="product_type" name="product_type" required>
                        </div>

                        <div class="mb-3">
                            <label for="cost_per_unit" class="form-label">Cost Per Unit</label>
                            <input type="number" class="form-control" id="cost_per_unit" name="cost_per_unit" required>
                        </div>

                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand">
                        </div>

                        <!-- Conditional Fields -->
                        <div id="excavatorFields">
                            <div class="mb-3">
                                <label for="ton" class="form-label">Ton</label>
                                <input type="number" class="form-control" id="ton" name="ton">
                            </div>
                            <div class="mb-3">
                                <label for="arm_length" class="form-label">Arm Length (m)</label>
                                <input type="text" class="form-control" id="arm_length" name="arm_length">
                            </div>
                            <div class="mb-3">
                                <label for="bucket_size" class="form-label">Bucket Size (m<sup>3</sup>)</label>
                                <input type="text" class="form-control" id="bucket_size" name="bucket_size">
                            </div>
                            <div class="mb-3">
                                <label for="dimension" class="form-label">Dimension (m)</label>
                                <input type="text" class="form-control" id="dimension" name="dimension">
                            </div>
                            <div class="mb-3">
                                <label for="fuel_tank_capacity" class="form-label">Fuel Tank Capacity (l)</label>
                                <input type="text" class="form-control" id="fuel_tank_capacity" name="fuel_tank_capacity">
                            </div>
                        </div>

                        <div id="attachmentFields">
                            <div class="mb-3">
                                <label for="compatible_excavator" class="form-label">Compatible Excavator</label>
                                <input type="text" class="form-control" id="compatible_excavator" name="compatible_excavator">
                            </div>
                        </div>

                        <div id="generalFields">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="text" class="form-control" id="weight" name="weight">
                            </div>
                            <div class="mb-3">
                                <label for="usage_conditions" class="form-label">Usage Conditions</label>
                                <textarea class="form-control" id="usage_conditions" name="usage_conditions" rows="3"></textarea>
                            </div>
                        </div>
                        <!-- End Conditional Fields -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProductChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>
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
      <section class="text-white">
        <div class="container text-center text-md-start mt-5">
          <!-- Grid row -->
          <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
              <!-- Content -->
              <h6 class="text-uppercase fw-bold mb-4">
                <i class="fas fa-gem me-3"></i>MKH Rental
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
                <a href="#!" class="text-reset">Angular</a>
              </p>
              <p>
                <a href="#!" class="text-reset">React</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Vue</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Laravel</a>
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
                <a href="#!" class="text-reset">Pricing</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Settings</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Orders</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Help</a>
              </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
              <!-- Links -->
              <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
              <p><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
              <p>
                <i class="fas fa-envelope me-3"></i>
                info@example.com
              </p>
              <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
              <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
            </div>
            <!-- Grid column -->
          </div>
          <!-- Grid row -->
        </div>
      </section>
      <!-- Section: Links  -->

      <!-- Copyright -->
      <div class="text-center text-white p-4" style="background-color: rgb(20, 24, 28);">
        © 2021 Copyright:
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
      </div>
      <!-- Copyright -->
    </footer>
    <script>

function uploadFile() {
        var formData = new FormData();
        var fileInput = document.getElementById('file');
        var file = fileInput.files[0];
        var product_id = $('#product_id').val();

        if (!file || !product_id) {
            Swal.fire('Error!', "No file or product ID provided.", 'error');
            return;
        }

        formData.append('file', file);
        formData.append('product_id', product_id);

        $.ajax({
            url: 'api/productImgUploadAPI.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var parsedResponse = JSON.parse(response);
                Swal.fire('Success!', parsedResponse.message, 'success');

                if (parsedResponse.success) {
                    $('#product_image_preview').attr('src', parsedResponse.profilePicSrc);
                }
            },
            error: function() {
                Swal.fire('Error!', 'An error occurred during the upload.', 'error');
            }
        });
    }



        function triggerFileInput() {
            document.getElementById('file').click();
        }

        function handleFileInputChange() {
            uploadFile();
        }

        function editProductDetails(product_id) {
            $.ajax({
                method: 'POST',
                url: 'api/getProductDetailsAPI.php',
                data: {
                    product_id: product_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Populate the form fields with the product data
                        var data = response.data;
                        $('#product_id').val(data.product_id);
                        $('#product_name').val(data.product_name);
                        $('#product_type').val(data.product_type);
                        $('#cost_per_unit').val(data.cost_per_unit);
                        $('#brand').val(data.brand);
                        $('#ton').val(data.ton);
                        $('#arm_length').val(data.arm_length);
                        $('#bucket_size').val(data.bucket_size);
                        $('#dimension').val(data.dimension);
                        $('#fuel_tank_capacity').val(data.fuel_tank_capacity);
                        $('#weight').val(data.weight);
                        $('#capacity_size').val(data.capacity_size);
                        $('#compatible_excavator').val(data.compatible_excavator);
                        $('#usage_conditions').val(data.usage_conditions);
                        $('#product_image_preview').attr('src', 'api/viewproductImgAPI.php?product_id=' + product_id);

                        // Show/Hide conditional fields based on product_type
                        var productType = data.product_type.toLowerCase();
                        if (productType === 'excavator') {
                            $('#excavatorFields').show();
                            $('#attachmentFields').hide();
                        } else if (productType === 'attachment') {
                            $('#attachmentFields').show();
                            $('#excavatorFields').hide();
                        } else {
                            $('#excavatorFields').hide();
                            $('#attachmentFields').hide();
                        }
                        $('#generalFields').show();

                        // Show the modal
                        $('#editProductModal').modal('show');
                    } else {
                        alert('Failed to load product details: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error during the AJAX request');
                }
            });
        }


        $('#saveProductChanges').on('click', function() {
        $.ajax({
            method: 'POST',
            url: 'api/updateProductDetailsAPI.php',
            data: $('#editProductForm').serialize(),
            success: function(response) {
                if (response === 'Success') {
                    Swal.fire('Updated!', 'Product details updated successfully.', 'success').then(() => {
                        $('#editProductModal').modal('hide');
                        location.reload(); // Reload the page to reflect the changes
                    });
                } else {
                    Swal.fire('Error!', 'Failed to update product details.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Error during the AJAX request.', 'error');
            }
        });
    });


      window.addEventListener('load', function() {
        // Scroll to the top of the page when it loads
        window.scrollTo(0, 0);
      });

      function goBack() {
        window.history.back();
      }
      $(document).ready(function() {
        // $(document).on('click', '#rentbutton', function() {
        //   var productId = <?php echo $productDetails['product_id']; ?>;
        //   redirectToRentForm(productId);
        // });

        // function redirectToRentForm(productId) {
        //   window.location.href = 'rentForm.php?product_id=' + productId;
        // }


        const displayReview = document.getElementById('display_review');
        // $(document).ready(function() {
        //   $.ajax({
        //     url: "api/SavedProductsAPI.php",
        //     method: "POST",
        //     data: {},
        //     dataType: "json",
        //     success: function(savedProducts) {
        //       const savedProductIds = savedProducts.map(savedProduct => savedProduct.product_id);
        //       console.log(savedProductIds);
        //     },
        //     error: function() {
        //       alert("Failed to load!");
        //     }
        //   });
        // });

        // window.toggleSave = function(product_id, button) {
        //   var user_id = $(button).data('userid');
        //   if (user_id) {
        //     $.ajax({
        //       method: "POST",
        //       url: "api/toggleSavedAPI.php",
        //       data: {
        //         user_id: user_id,
        //         product_id: product_id
        //       },
        //       dataType: "json",
        //       success: function(response) {
        //         if (response.success) {
        //           alert(response.message);
        //           var heartIcon = $(button).find('i');
        //           heartIcon.removeClass('bi-bookmark bi-bookmark-fill');
        //           heartIcon.addClass(response.isSaved ? 'bi-bookmark-fill' : 'bi-bookmark');
        //           location.reload();
        //         } else {
        //           alert("Error: " + response.message);
        //         }
        //       },
        //       error: function(xhr, status, error) {
        //         alert("AJAX request failed with status: " + status + "\nError: " + error);
        //       }
        //     });
        //   } else {
        //     alert("Please Sign in to Save.");
        //   }
        // };


        // var rating_value = 0;

        // $('#add_review').click(function() {
        //   <?php if (isset($_SESSION['user_id'])) { ?>
        //     $('#myModal').modal('show');
        //   <?php } else { ?>
        //     alert('Please log in to add a review.');
        //   <?php } ?>
        // });

        // $(document).on('mouseenter', '.submit_star', function() {
        //   var rating = $(this).data('rating');
        //   resetStar();
        //   for (var i = 1; i <= rating; i++) {
        //     $('#submit_star_' + i).addClass('text-warning');
        //   }
        // });

        // function resetStar() {
        //   for (var i = 1; i <= 5; i++) {
        //     $('#submit_star_' + i).removeClass('text-warning');
        //     $('#submit_star_' + i).addClass('star-light');
        //   }
        // }

        // $(document).on('click', '.submit_star', function() {
        //   rating_value = $(this).data('rating');
        //   for (var i = 1; i <= rating_value; i++) {
        //     $('#submit_star_' + i).addClass('text-warning');
        //   }
        // });

        // $('#sendReview').click(function() {
        //   // Ensure these variables are correctly retrieved from PHP
        //   var user_id = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
        //   var product_id = <?php echo isset($_GET['product_id']) ? json_encode($_GET['product_id']) : 'null'; ?>;
        //   var message = $('#message').val();
        //   var rating = rating_value; // Update this line to get rating from rating_value

        //   if (!user_id || !message) {
        //     alert('Please, fill both fields');
        //     return false;
        //   }

        //   $.ajax({
        //     url: 'api/postreviewAPI.php',
        //     method: 'POST',
        //     data: {
        //       rating: rating,
        //       user_id: user_id,
        //       product_id: product_id,
        //       message: message
        //     },
        //     success: function(data) {
        //       $('#myModal').modal('hide');
        //       displayReview.scrollTop = 0;
        //       console.log(data);
        //       loadData();
        //     },
        //     error: function(jqXHR, textStatus, errorThrown) {
        //       console.error('Error:', textStatus, errorThrown);
        //       alert('An error occurred. Please try again.');
        //     }
        //   });
        // });

        function loadData() {
          var product_id = <?php echo isset($_GET['product_id']) ? json_encode($_GET['product_id']) : 'null'; ?>;
          $.ajax({
            url: 'api/postreviewAPI.php',
            method: "POST", // Changed to GET
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

        // Add event listener to the escape button
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>