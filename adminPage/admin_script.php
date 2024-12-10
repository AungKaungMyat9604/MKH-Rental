<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loadingScreen = document.getElementById('loadingScreen');

        // Set a delay of 2 or 3 seconds before hiding the loading screen
        setTimeout(function() {
            loadingScreen.style.display = 'none'; // Hide loading screen after delay
        }, 1000); // 2000 milliseconds = 2 seconds
        // or you can use 3000 for 3 seconds delay
        // }, 3000);
    });

    // $(window).on('load', function() {
    //     setTimeout(function() {
    //         $('.sidebar-link.filter-option').each(function() {
    //             if ($(this).text().trim() === "Main Dashboard") {
    //                 $(this).trigger('click');
    //                 $('.sidebar-link').removeClass('active'); // Remove active class from all links
    //                 $(this).addClass('active'); // Set this link as active manually
    //             }
    //         });
    //     }, 500);
    // });

    function loadMainDashboard() {
        $('#header').text('Main Dashboard');
        var mainContentContainer = $("#allhistorysdisplay"); // Assuming this is the container where the content should be loaded
        mainContentContainer.empty(); // Clear previous content
        var addProductButton = $("#addProductButton");

        addProductButton.empty();

        $.ajax({
            url: "api/getDashboardStatsAPI.php",
            method: "POST",
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    var dashboardHTML = `
                                <div class="row g-3 d-flex align-items-center">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="card dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title fs-2">Total Rents</h3>
                                                <p class="card-text fs-3">${data.data.total_rents}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="card  dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title fs-2">Total Customers</h3>
                                                <p class="card-text fs-3">${data.data.total_customers}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="card dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title fs-2">Total Products</h3>
                                                <p class="card-text fs-3">${data.data.total_products}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="card dashboard">
                                            <div class="card-body">
                                                <h3 class="card-title fs-2">Total Cash</h3>
                                                <p class="card-text fs-3">${data.data.total_cost} Ks</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;


                    mainContentContainer.html(dashboardHTML);
                } else {
                    alert('Failed to load dashboard data: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error occurred while fetching dashboard stats: ' + error);
            }
        });
    }

    function goBack() {
        window.history.back();
    }


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

        // Prepare the Google Maps URL
        const googleMapsUrl = `https://www.google.com/maps?q=${latLng.lat},${latLng.lng}`;
        $('#openInGoogleMaps').off('click').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Google Maps?',
                text: "Do you want to open this location in Google Maps?",
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

    }

    function rentmanagement() {
        // Load saved products on page ready
        $('#allhistorys').ready(function() {
            $('#header').text('Rent Management');
            var allHistorysContainer = $("#allhistorysdisplay");
            var addProductButton = $("#addProductButton");
            allHistorysContainer.empty();
            addProductButton.empty();
            $.ajax({
                url: "api/adminallhistorysAPI.php",
                method: "POST",
                data: {},
                dataType: "json",
                success: function(data) {
                    $.each(data, function(index, history) {
                        var declineButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-md card-link decline_button">Decline</a>' : '';
                        var approveButton = history.rent_status === "pending" ? '<a  class="btn btn-primary btn-md card-link approvebutton">Approve</a>' : '';

                        var doneButton = history.rent_status === "approved / ongoing" ? '<a  class="btn btn-primary btn-md card-link donebutton">Finished?</a>' : '';

                        var sdeclineButton = history.rent_status === "pending" ? '<a  class="btn btn-danger btn-sm card-link decline_button">Decline</a>' : '';
                        var sapproveButton = history.rent_status === "pending" ? '<a  class="btn btn-primary btn-sm card-link approvebutton">Approve</a>' : '';

                        var sdoneButton = history.rent_status === "approved / ongoing" ? '<a  class="btn btn-primary btn-sm card-link donebutton">Finished?</a>' : '';

                        var viewLocationButton = history.latitude && history.longitude ? '<a  class="btn btn-outline-primary btn-md card-link viewlocationbutton" data-bs-toggle="modal" data-bs-target="#viewLocationModal"><i class="bi bi-geo-alt"></i></a>' : '';
                        var sviewLocationButton = history.latitude && history.longitude ? '<a class="btn btn-outline-primary btn-sm card-link viewlocationbutton" data-bs-toggle="modal" data-bs-target="#viewLocationModal"><i class="bi bi-geo-alt"></i> View Location</a>' : '';

                        // New section: Check if transportation is needed
                        var transportationText = history.need_transportation == 1 ?
                            '<p class="mt-2 text-danger">Transportation Needed</p>' :
                            '<p class="mt-2">No Transportation Needed</p>';

                        var allHistoryCard = $(
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
                            '               <p class="mt-2">Name : ' + history.rfullname + '</p>' +
                            '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
                            '               <p class="mt-2 text-success">Phone Number : ' + history.rphone + '</p>' +
                            '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                            '               <p class="mt-2">End Date : ' + history.end_date + '</p>' +
                            transportationText + // Include the transportation text here
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
                            '               <div class="d-flex justify-content-end align-items-center">' + // Stack buttons on small screens
                            sviewLocationButton +
                            '               </div>' +
                            '               <div class="d-flex justify-content-center align-items-center">' + // Stack buttons on small screens
                            '                   <a  class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                            '                   <a  class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewcusbutton"><i class="fa-solid fa-eye"></i> Customer</a>' +
                            '               </div>' +
                            '               <div class="d-flex justify-content-center align-items-center">' +
                            sapproveButton +
                            sdeclineButton +
                            sdoneButton +
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
                            '               <p class="mt-2">Name : ' + history.rfullname + '</p>' +
                            '               <p class="mt-2">Rent Address : ' + history.rent_address + '</p>' +
                            '               <p class="mt-2 text-success">Phone Number : ' + history.rphone + '</p>' +
                            '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                            '               <p class="mt-2">End Date : ' + history.end_date + '</p>' +
                            transportationText + // Include the transportation text here as well
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
                            viewLocationButton +
                            '                           <a  class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewbutton"  data-bs-toggle="modal" data-bs-target="#viewCusModal"><i class="fa-solid fa-eye"></i> Product</a>' +
                            '                   <a  class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewcusbutton"><i class="fa-solid fa-eye"></i> Customer</a>' +
                            approveButton +
                            declineButton +
                            doneButton +
                            '                       </div>' +
                            '                   </div>' +
                            '               </div>' +
                            '           </div>' +
                            '       </div>' +
                            '   </div>' +
                            '</div>'
                        );

                        // Handling the click events
                        allHistoryCard.find('.viewbutton').on('click', function() {
                            redirectToProductDetails(history.product_id);
                        });

                        allHistoryCard.find('.viewcusbutton').on('click', function() {
                            viewcustomer(history.user_id);
                        });

                        allHistoryCard.find('.decline_button').on('click', function() {
                            $('#declineModal').modal('show');
                            declineRentForm(history.product_id, history.product_name, history.rent_forms_id, history.remail, history.rfullname);
                        });

                        allHistoryCard.find('.approvebutton').on('click', function() {
                            approveRentForm(history.product_id, history.product_name, history.rent_forms_id, history.remail, history.rfullname);
                        });

                        allHistoryCard.find('.donebutton').on('click', function() {
                            doneRentForm(history.product_id, history.product_name, history.rent_forms_id, history.remail, history.rfullname);
                        });

                        allHistoryCard.find('.viewlocationbutton').on('click', function() {
                            // Pass the latitude and longitude to the modal
                            var latLng = {
                                lat: parseFloat(history.latitude),
                                lng: parseFloat(history.longitude)
                            };
                            initAdminMap(latLng);
                        });

                        allHistorysContainer.append(allHistoryCard);
                    });
                }
            });
        });
    }


    function viewcustomer(user_id) {
        $.ajax({
            method: 'POST',
            url: 'api/viewCusAPI.php',
            data: {
                user_id: user_id
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Update the UI with the customer information
                    $('#fullname').text(data.fullname);
                    $('#email').text(data.email);
                    $('#phone').text(data.phone);
                    $('#city').text(data.city);
                    $('#township').text(data.township);

                    // Update the profile picture source
                    $('#profilePic').attr('src', 'api/viewprofilepictureAPI.php?user_id=' + user_id);

                    // Show the modal
                    $('#viewCusModal').modal('show');
                } else {
                    alert('Failed to load customer information');
                }
            },
            error: function() {
                alert('Error during the AJAX request');
            }
        });
    }



    function declineRentForm(product_id, product_name, form_id, remail, rfullname) {
        $('#uploadMessage').on('click', function() {
            var message_why = $('#message_why').val();
            $('#loadingScreen').show();
            $.ajax({
                method: 'POST',
                url: 'api/uploadDeclineMessageAPI.php',
                data: {
                    product_id: product_id,
                    product_name: product_name,
                    form_id: form_id,
                    remail: remail,
                    rfullname: rfullname,
                    message_why: message_why
                },
                success: function(response) {
                    if (response === 'Success') {

                        // Show the loading spinner
                        $('#loadingScreen').hide();
                        Swal.fire('Declined!', 'Form has been declined!', 'success').then(() => {
                            $('#declineModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        alert('Failed to decline');
                    }
                },
                error: function() {
                    alert('Error during the AJAX request');
                }
            });
        });
    }

    function approveRentForm(product_id, product_name, form_id, remail, rfullname) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to approve this form?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show the loading spinner
                $('#loadingScreen').show();
                $.ajax({
                        method: 'POST',
                        url: 'api/adminApproveFormAPI.php',
                        data: {
                            product_id: product_id,
                            product_name: product_name,
                            form_id: form_id,
                            remail: remail,
                            rfullname: rfullname
                        }
                    })
                    .done(function(msg) { // .done() method should be here
                        if (msg.trim().startsWith('Success')) {
                            $('#loadingScreen').hide();
                            Swal.fire('Approved!', 'Form has been approved!', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to approve the form.', 'error');
                        }
                    })
                    .fail(function() { // Handle any errors during the AJAX request
                        Swal.fire('Error!', 'Error during the AJAX request.', 'error');
                    });
            }
        });
    }

    function doneRentForm(product_id, product_name, form_id, remail, rfullname) {
        Swal.fire({
            title: 'Is it finished?',
            text: "Has the product returned?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show the loading spinner
                $('#loadingScreen').show();
                $.ajax({
                        method: 'POST',
                        url: 'api/adminDoneFormAPI.php',
                        data: {
                            product_id: product_id,
                            product_name: product_name,
                            form_id: form_id,
                            remail: remail,
                            rfullname: rfullname
                        }
                    })
                    .done(function(msg) { // .done() method should be here
                        if (msg.trim().startsWith('Success')) {
                            $('#loadingScreen').hide();
                            Swal.fire('Approved!', 'Form has been set to Finished!', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to set the form "Finished!".', 'error');
                        }
                    })
                    .fail(function() { // Handle any errors during the AJAX request
                        Swal.fire('Error!', 'Error during the AJAX request.', 'error');
                    });
            }
        });
    }



    function redirectToProductDetails(productId) {
        if (productId) {
            window.location.href = 'admin_product_details.php?product_id=' + productId;
        } else {
            console.error('Product ID is undefined');
        }
    }

    function productmanagement() {
        var addProductButton = $("#addProductButton");
        addProductButton.empty();
        // Load saved products on page ready
        $('#allhistorys').ready(function() {
            $('#header').text('Product Management');
            var allHistorysContainer = $("#allhistorysdisplay");
            var addProductButton = $("#addProductButton");
            var addProduct = '<a href="#" class="btn btn-primary btn-md addbutton" onclick="addProduct();"><i class="fa-solid fa-plus"></i> Product</a>';
            allHistorysContainer.empty();
            $.ajax({
                url: "api/allproductsAPI.php",
                method: "POST",
                data: {},
                dataType: "json",
                success: function(data) {
                    $.each(data, function(index, product) {
                        var productId = product.product_id;
                        var activeIconClass = product.is_active ? 'bi-circle-fill text-success' : 'bi-circle-fill text-danger';
                        var editButton = '<a  class="btn btn-primary btn-md card-link editbutton">Edit</a>';
                        var rating = product.rating ? product.rating : 0;
                        var imgsrc = `api/viewproductImgAPI.php?product_id=${encodeURIComponent(productId)}`;
                        var delButton = '<a  class="btn btn-danger btn-md card-link deletebutton"><i class="fa-solid fa-trash"></i></a>'

                        var allProductCard = $(
                            '<div class="col-lg-3 col-md-4 col-sm-6 col-12 my-2 d-flex justify-content-center align-items-center product-card" data-product-id="' + productId + '">' +
                            '   <div class="card d-flex flex-column">' +
                            '     <div class="img-container">' +
                            '       <img src="' + imgsrc + '" class="card-img-top" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;">' +
                            '     </div>' +
                            '       <div class="card-body d-flex justify-content-start">' +
                            '           <h5 class="card-title"><i class="bi ' + activeIconClass + '"></i> ' + product.product_name + '</h5>' +
                            '               <hr class="divider-custom">' +
                            '           <div class="card-text mt-1">' +
                            '               <p>Class: ' + (product.product_type || 'N/A') + '</p>' + // Handle cases where product_type might be missing
                            '               <p>Type: ' + (product.type || 'N/A') + '</p>' + // Handle cases where type might be missing
                            '               <p class="fst-italic fw-bold">' + (product.usage_conditions || 'N/A') + '</p>' + // Handle cases where usage_conditions might be missing
                            '               <p><div class="fixed-rating">' + generateStarRating(rating) + ' ' + rating + ' stars</div></p>' +
                            '           </div>' +
                            '           <div class="d-flex justify-content-center align-items-center">' +
                            '               <a class="btn btn-primary btn-md card-link" id="morebutton">View</a>' +
                            editButton +
                            delButton +
                            '           </div>' +
                            '       </div>' +
                            '   </div>' +
                            '</div>'
                        );


                        allProductCard.find('#morebutton').on('click', function() {
                            redirectToProductDetails(productId);
                        });
                        allProductCard.find('.editbutton').on('click', function() {
                            editProductDetails(productId);
                        });
                        allProductCard.find('.deletebutton').on('click', function() {
                            deleteProduct(productId);
                        });


                        allHistorysContainer.append(allProductCard);
                    });
                    addProductButton.append(addProduct);
                }
            });
        });
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
                    $('#type').val(data.type);
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



    $('#product_type_add').on('change', function() {
        var productType = $(this).val().toLowerCase();
        if (productType === 'excavator') {
            $('#excavatorFieldsAdd').show();
            $('#attachmentFieldsAdd').hide();
        } else if (productType === 'attachment') {
            $('#attachmentFieldsAdd').show();
            $('#excavatorFieldsAdd').hide();
        } else {
            $('#excavatorFieldsAdd').hide();
            $('#attachmentFieldsAdd').hide();
        }
        $('#generalFieldsAdd').show();
    });

    function triggerFileInputAdd() {
        $('#fileAdd').click();
    }

    // Function to handle image preview
    function handleFileInputChangeAdd() {
        var file = document.getElementById('fileAdd').files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#product_image_preview_add').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function addProduct() {
        $('#addProductModal').modal('show');

        $('#saveNewProduct').off('click').on('click', function() {
            var formData = new FormData($('#addProductForm')[0]);

            $.ajax({
                method: 'POST',
                url: 'api/addProductAPI.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        var parsedResponse = JSON.parse(response);

                        if (parsedResponse.success) {
                            Swal.fire('Added!', 'Product added successfully!', 'success').then(() => {
                                $('#addProductModal').modal('hide');
                                location.reload(); // Reload the page to reflect the changes
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to add Product: ' + parsedResponse.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error!', 'Failed to parse the response: ' + e.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Error during the AJAX request.', 'error');
                }
            });
        });
    }


    function deleteProduct(product_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to delete this product?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delProductAPI.php',
                    method: 'POST',
                    data: {
                        product_id: product_id
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            Swal.fire('Deleted!', 'Product has been deleted successfully.', 'success').then(() => {
                                location.reload(); // Reload the page to reflect the changes
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete product: ' + data.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error!', "Error occurred while deleting the product: " + error, 'error');
                    }
                });
            }
        });
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>