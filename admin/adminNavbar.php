<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="images/Logo.png" class="img-fluid" alt="MKH Group" style="width: auto; height: 55px; margin-left: 5px; border-radius: 15px;" />
            <h3 class="mb-0 ms-4 d-none d-md-block">Admin Page</h3>
        </a>
        <div class="d-flex align-items-center order-lg-2">
            <?php if (isset($_SESSION['email'])) { ?>
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
                        <li><hr class="dropdown-divider"></li>
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
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width:240px;">
                        <li><a class="dropdown-item" href="signin.php"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="signup.php"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>Sign Up</a></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</nav>
