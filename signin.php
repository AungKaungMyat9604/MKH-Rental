<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
  $user_id = $_SESSION['user_id'];
  $fullnameParts = explode(" ", $_SESSION['fullname']);
  $firstName = $fullnameParts[0];
  echo '<script>window.history.back();</script>';
} else {
  $user_id = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MKH Rental_Log In</title>
  <link rel="icon" type="image/png" href="images/small_logo.png">
  <style>
    body,
    html {
      height: 100%;
      margin: 0;
    }

    .background {
      background-image: url('images/sign_background.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding-top: 130px;
      /* Add padding to top to create space between navbar and form */
      padding-bottom: 100px;
      padding-left: 5px;
      padding-right: 5px;
    }

    .sign-in-box {
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(8px);
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
      /* Light gray background */
      color: #007bff;
      /* Bootstrap primary color for text */
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
  <script src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div id="accessibility-icon" class="accessibility-circle">
    <i class="fas fa-universal-access"></i>
</div>

<div id="zoom-controls" class="circle-menu">
    <button id="zoom-in" class="circle-button"><i class="bi bi-zoom-in"></i></button>
    <button id="zoom-out" class="circle-button mx-2"><i class="bi bi-zoom-out"></i></button>
</div>
  <div class="background">
    <!--NAVBAR-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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

                <li><a class="dropdown-item" href="signout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a></li>
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
              <a class="nav-link " aria-current="page" href="home.php">Home</a>
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

    <div class="container">
      <div class="row justify-content-end">
        <div class="col-lg-5 col-md-12 col-12 sign-in-box">
          <h2 class="text-center mt-3">Log In</h2>
          <form>
            <div class="mb-3 mx-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" placeholder="name@example.com">
            </div>
            <div class="mb-3 mx-3">
              <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" placeholder="Password">
                <button id="toggle-password" type="button" class="btn btn-outline-secondary">
                  <i class="fa-solid fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="gap-2 mt-4 mx-3">
              <button id="login-btn" type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-right-to-bracket me-2"></i>Log In</button>
            </div>
          </form>
          <div class="text-center mt-3">
            <p>Not registered?
              <a href="signup.php">Create an account!</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'chatbot.php' ?>

  <script>
    $(document).ready(function() {
      $('#toggle-password').click(function() {
        var passwordInput = $('#password');
        var icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    
      $('#login-btn').click(function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var passwordRegex = /^.{8,}$/;

        if (!emailRegex.test(email) || !passwordRegex.test(password)) {
          Swal.fire({
            icon: 'warning',
            title: 'Invalid Input',
            text: 'Please fill out all fields with valid data.',
            confirmButtonText: 'OK'
          });
        } else {
          $.ajax({
              method: 'POST',
              url: 'api/signinAPI.php',
              data: {
                email: email,
                password: password
              }
            })
            .done(function(response) {
              if (response == 'admin') {
                Swal.fire({
                  icon: 'success',
                  title: 'Login Successful',
                  text: 'Redirecting to admin dashboard...',
                  confirmButtonText: 'OK'
                }).then(() => {
                  window.location.href = 'adminPage/admin_dashboard.php';
                });
              } else if (response == 'customer') {
                Swal.fire({
                  icon: 'success',
                  title: 'Login Successful',
                  text: 'Redirecting....',
                  confirmButtonText: 'OK'
                }).then(() => {
                  window.history.back();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Login Failed',
                  text: response,
                  confirmButtonText: 'OK'
                });
              }
            });
        }
      });
    });
  </script>



  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

</html>