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
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MKH Rental_Profile Settings</title>
    <link rel="icon" type="image/png" href="images/small_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/profileSetting.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/484c2b0aba.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          <img src="images/Logo.png" class="img-fluid" alt="MKH Group" style="height: 55px; border-radius: 15px;" />
        </a>

        <div class="d-flex align-items-center order-lg-2">
          <?php if (isset($_SESSION['email'])) { ?>
            <li class="nav-item">
              <a class="ms-4" href="savedList.php">
                <i class="fa-solid fa-bookmark text-warning fs-3"></i>
                <span class="badge bg-primary badge-number" style="margin-left:-8px;">0</span>
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
                <li><a href="savedList.php" class="dropdown-item" id="savedDropdown"><i class="fa-solid fa-bookmark me-2"></i> Saved List</a></li>
                <li><a class="dropdown-item" href="renthistory.php"><i class="fa-solid fa-clock-rotate-left me-2"></i>Rent History</a></li>
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
                  <i class="fa-solid fa-caret-down p-1"></i>Sign In
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
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
          </ul>

          <form class=" d-none d-lg-block" id="searchForm" action="products.php" method="GET" style="margin-top:auto; margin-bottom: auto;">
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

    <div id="main" style="margin-top: 90px;">
      <nav aria-label="breadcrumb" class="mx-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page"><a href="profilesetting.php">Profile Settings</a></li>
        </ol>
      </nav>
      <div class="mx-4">
        <button id="backButton" class="btn btn-outline-secondary" onclick="goBack()">
          <i class="bi bi-arrow-left"></i> Back
        </button>
      </div>
      <div class="container-fluid" style="padding: 25px; padding-bottom: 150px;">
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card mb-4" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(5px); border-radius: 15px;">
              <div class="card-body text-center">
                <img id="profilePic" src="api/viewprofilepictureAPI.php?user_id=<?php echo urlencode($user_id); ?>" alt="Click to Upload" class="rounded-circle img-fluid" style="width: 130px; height: 130px; cursor: pointer;" onclick="triggerFileInput()">
                <form id="uploadForm" style="display: none;" enctype="multipart/form-data">
                  <input type="file" name="file" id="file" onchange="handleFileInputChange()" required>
                </form>
                <p style="margin:0;"><i class="fa-solid fa-chevron-up"></i></p>
                <p style="margin:0;" class="text-muted">Click on Image to Edit</p>
                <hr class="divider-custom">
                <h5 class="my-3 fs-3"><?php echo $_SESSION['fullname']; ?></h5>
                <p class="mb-1">Customer</p>
                <p class="mb-4"><?php echo $_SESSION['city']; ?>, <?php echo $_SESSION['township']; ?></p>
                <div class="d-flex justify-content-center mb-2">
                  <button type="button" class="btn btn-danger" id="delete"><i class="fa-regular fa-square-minus me-1"></i>Deactivate</button>
                </div>
                <div class="d-flex justify-content-center mb-2">
                  <button type="button" class="btn btn-success" id="changepwdiv"><i class="fa-solid fa-key me-1"></i>Change Password</button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8 col-md-6 col-sm-12">
            <div class="card mb-4" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(5px); border-radius: 15px;">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Full Name</p>
                  </div>
                  <div class="col-sm-9">
                    <input id="fullname" type="text" class="form-control" value="<?php echo $_SESSION['fullname']; ?>">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Email</p>
                  </div>
                  <div class="col-sm-9">
                    <input id="email" type="text" class="form-control text-success" value="<?php echo $_SESSION['email']; ?>" readonly>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Phone</p>
                  </div>
                  <div class="col-sm-9">
                    <input id="phone" type="text" class="form-control" value="<?php echo $_SESSION['phone']; ?>">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">City</p>
                  </div>
                  <div class="col-sm-9">
                    <input id="city" type="text" class="form-control" value="<?php echo $_SESSION['city']; ?>">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Township</p>
                  </div>
                  <div class="col-sm-9">
                    <input id="township" type="text" class="form-control" value="<?php echo $_SESSION['township']; ?>">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-primary ms-1" id="update"> <i class="fa-regular fa-circle-up me-1"></i>Update</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include 'chatbot.php'; ?>

    <script>
      function goBack() {
        window.history.back();
      }

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

      $(document).ready(function() {
        $('#update').click(function(e) {
          e.preventDefault();
          var fullname = $('#fullname').val();
          var email = $('#email').val();
          var phone = $('#phone').val();
          var city = $('#city').val();
          var township = $('#township').val();

          $.ajax({
              method: "POST",
              url: "api/profilesettingupdateAPI.php",
              data: {
                fullname: fullname,
                email: email,
                phone: phone,
                city: city,
                township: township,
              }
            })
            .done(function(response) {
              if (response.trim() === 'Success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Update Completed!',
                  text: 'Your profile has been updated successfully.',
                  confirmButtonText: 'OK'
                }).then(() => {
                  window.location.reload();
                });

              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response,
                  confirmButtonText: 'OK'
                });
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Failed to communicate with the server: ' + textStatus,
                confirmButtonText: 'OK'
              });
            });

        });

        $('#delete').click(function() {
          Swal.fire({
            title: 'Confirm Account Deactivation',
            input: 'password',
            inputLabel: 'Enter your password to confirm',
            inputPlaceholder: 'Password',
            inputAttributes: {
              required: true,
              autocomplete: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Deactivate',
            showLoaderOnConfirm: true,
            customClass: {
              confirmButton: 'btn btn-danger' // Bootstrap primary button
            },
            preConfirm: (confirmPassword) => {
              return $.ajax({
                  method: 'POST',
                  url: 'api/profilesettingdeleteAPI.php',
                  data: {
                    confirmPassword: confirmPassword
                  }
                })
                .done(function(response) {
                  if (response.trim() === 'Success') {
                    Swal.fire('Deleted!', 'Your account has been deactivated.', 'success').then(() => {
                      window.location.href = 'home.php';
                    });
                  } else {
                    Swal.fire('Error', response, 'error');
                  }
                })
                .fail(function() {
                  Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          });

        });

        $('#changepwdiv').click(function() {
          Swal.fire({
            title: 'Change Password',
            input: 'password',
            inputLabel: 'Old Password',
            inputAttributes: {
              id: 'oldPassword',
              placeholder: 'Old Password',
              required: 'required'
            },
            showCancelButton: true,
            confirmButtonText: 'Next',
            cancelButtonText: 'Cancel',
            preConfirm: (oldPassword) => {
              if (!oldPassword) {
                Swal.showValidationMessage('Please enter your old password');
                return false;
              }
              return oldPassword;
            }
          }).then((result) => {
            if (result.isConfirmed) {
              const oldPassword = result.value;

              Swal.fire({
                title: 'Change Password',
                html: '<input id="newPassword" type="password" class="swal2-input" placeholder="New Password" required style="width:80%; font-size: 14px;" />' +
                '<input id="confirmPassword" type="password" class="swal2-input" placeholder="Confirm New Password" required style="width:80%; font-size: 14px;" />',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Change Password',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                  const newPassword = Swal.getPopup().querySelector('#newPassword').value;
                  const confirmPassword = Swal.getPopup().querySelector('#confirmPassword').value;

                  if (!newPassword || !confirmPassword) {
                    Swal.showValidationMessage('Please enter all fields');
                    return false;
                  }

                  if (newPassword.length < 8) {
                    Swal.showValidationMessage('New Password must be at least 8 characters long');
                    return false;
                  }

                  if (newPassword !== confirmPassword) {
                    Swal.showValidationMessage('New Password and Confirm Password do not match');
                    return false;
                  }

                  return {
                    oldPassword: oldPassword,
                    newPassword: newPassword
                  };
                }
              }).then((result) => {
                if (result.isConfirmed) {
                  const {
                    oldPassword,
                    newPassword
                  } = result.value;

                  $.ajax({
                    method: 'POST',
                    url: 'api/passwordupdateAPI.php',
                    data: {
                      oldPassword: oldPassword,
                      newPassword: newPassword,
                    },
                    success: function(response) {
                      if (response === 'Success') {
                        Swal.fire({
                          icon: 'success',
                          title: 'Password Changed',
                          text: 'Password changed successfully!',
                          confirmButtonText: 'OK'
                        });
                      } else if (response === 'Invalid old password') {
                        Swal.fire({
                          icon: 'error',
                          title: 'Invalid Old Password',
                          text: 'The old password you entered is incorrect.',
                          confirmButtonText: 'OK'
                        });
                      } else if (response === 'User not found') {
                        Swal.fire({
                          icon: 'error',
                          title: 'User Not Found',
                          text: 'The user was not found.',
                          confirmButtonText: 'OK'
                        });
                      } else if (response === 'Error updating password') {
                        Swal.fire({
                          icon: 'error',
                          title: 'Update Error',
                          text: 'An error occurred while updating the password. Please try again.',
                          confirmButtonText: 'OK'
                        });
                      } else {
                        Swal.fire({
                          icon: 'error',
                          title: 'Unexpected Error',
                          text: 'An unexpected error occurred. Please try again.',
                          confirmButtonText: 'OK'
                        });
                      }
                    },
                    error: function() {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.',
                        confirmButtonText: 'OK'
                      });
                    }
                  });
                }
              });
            }
          });
        });

      });

      function uploadFile() {
        var formData = new FormData();
        var fileInput = document.getElementById('file');
        var file = fileInput.files[0];
        formData.append('file', file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/profilepictureUploadAPI.php', true);

        xhr.onload = function() {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            Swal.fire({
              icon: response.success ? 'success' : 'error',
              title: response.success ? 'Uploaded' : 'Error',
              text: response.message,
              confirmButtonText: 'OK'
            }).then(() => {
              if (response.success) {
                window.location.reload();
                document.getElementById('profilePic').src = response.profilePicSrc;
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred during the upload.',
              confirmButtonText: 'OK'
            });
          }
        };

        xhr.send(formData);
      }

      function triggerFileInput() {
        document.getElementById('file').click();
      }

      function handleFileInputChange() {
        uploadFile();
      }
    </script>

    <?php include 'logout-link.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>

  </html>
<?php
} else {
  header("Location: signin.php");
}
?>