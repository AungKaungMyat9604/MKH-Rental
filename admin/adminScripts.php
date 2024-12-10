<script>
        function goBack() {
            window.history.back();
        }


        function uploadFile() {
            var formData = new FormData();
            var fileInput = document.getElementById('file');
            var file = fileInput.files[0];
            var product_id = $('#product_id').val(); // Get the product ID from the form

            if (!file || !product_id) {
                alert("No file or product ID provided.");
                return;
            }

            formData.append('file', file);
            formData.append('product_id', product_id); // Ensure product_id is appended to FormData

            $.ajax({
                url: 'api/productImgUploadAPI.php',
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Prevent jQuery from setting the content type header, which lets the browser set it to multipart/form-data
                success: function(response) {
                    var parsedResponse = JSON.parse(response);
                    alert(parsedResponse.message);

                    if (parsedResponse.success) {
                        // Update product image on successful upload
                        $('#product_image_preview').attr('src', parsedResponse.profilePicSrc);
                    }
                },
                error: function() {
                    alert('An error occurred during the upload.');
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
                const confirmRedirect = confirm("Are you sure you want to open this location in Google Maps?");
                if (confirmRedirect) {
                    window.open(googleMapsUrl, '_blank');
                }
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
                            var declineButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-danger btn-md card-link declinebutton" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</a>' : '';
                            var approveButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-primary btn-md card-link approvebutton">Approve</a>' : '';
                            var whyButton = history.rent_status === "declined" ?
                                '<a href="#" class="btn btn-primary btn-md card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';

                            var extensionButton = (history.rent_status === "approved / ongoing") ? '<a href="#" class="btn btn-primary btn-md card-link extensionbutton" data-bs-toggle="modal" data-bs-target="#extensionModal"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                            var rentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a href="#" class="btn btn-primary btn-md card-link rentbutton" ><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

                            var sdeclineButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-danger btn-sm card-link declinebutton" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</a>' : '';
                            var sapproveButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-primary btn-sm card-link approvebutton">Approve</a>' : '';
                            var swhyButton = history.rent_status === "declined" ?
                                '<a href="#" class="btn btn-primary btn-sm card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';
                            var sextensionButton = (history.rent_status === "approved / ongoing") ? '<a href="#" class="btn btn-primary btn-sm card-link extensionbutton" data-bs-toggle="modal" data-bs-target="#extensionModal"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                            var srentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a href="#" class="btn btn-primary btn-sm card-link rentbutton"><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';
                            var viewLocationButton = history.latitude && history.longitude ? '<a href="#" class="btn btn-outline-primary btn-md card-link viewlocationbutton" data-bs-toggle="modal" data-bs-target="#viewLocationModal"><i class="bi bi-geo-alt"></i></a>' : '';
                            var sviewLocationButton = history.latitude && history.longitude ? '<a href="#" class="btn btn-outline-primary btn-sm card-link viewlocationbutton" data-bs-toggle="modal" data-bs-target="#viewLocationModal"><i class="bi bi-geo-alt"></i> View Location</a>' : '';

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
                                '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                                '               <p class="mt-2">End Date : ' + history.end_date + '</p>' +
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
                                '                   <a href="#" class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                                '                   <a href="#" class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewcusbutton"><i class="fa-solid fa-eye"></i> Customer</a>' +
                                '               </div>' +
                                '               <div class="d-flex justify-content-center align-items-center">' +
                                sapproveButton +
                                sdeclineButton +
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
                                '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
                                '               <p class="mt-2">End Date : ' + history.end_date + '</p>' +
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
                                '                           <a href="#" class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewbutton"  data-bs-toggle="modal" data-bs-target="#viewCusModal"><i class="fa-solid fa-eye"></i> Product</a>' +
                                '                   <a href="#" class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewcusbutton"><i class="fa-solid fa-eye"></i> Customer</a>' +
                                approveButton +
                                declineButton +
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

                            allHistoryCard.find('.declinebutton').on('click', function() {
                                declineRentForm(history.product_id, history.rent_forms_id);
                            });

                            // Update the click event for the #whybutton to show the modal
                            allHistoryCard.find('.whybutton').on('click', function() {
                                // If the decline reason is available, set it in the modal
                                var declineReason = history.message_why || "No specific reason provided."; // Replace with actual reason
                                $('#whyModal .modal-body #declineReason').text(declineReason);
                            });

                            allHistoryCard.find('.rentbutton').on('click', function() {
                                redirectToRentForm(history.product_id);
                            });

                            allHistoryCard.find('.extensionbutton').on('click', function() {
                                $('#extensionProductId').val(history.product_id);
                                $('#totalHours').text(history.renting_hours + ' Hrs');
                                $('#endDate').text(history.end_date); // Show the current end date
                                $('#totalCost').text(history.total_cost + ' Kyats'); // Show the current total cost
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



        function declineRentForm(product_id, form_id) {
            $('#uploadMessage').on('click', function() {
                var message_why = $('#message_why').val();
                $.ajax({
                    method: 'POST',
                    url: 'api/uploadDeclineMessageAPI.php',
                    data: {
                        product_id: product_id,
                        form_id: form_id,
                        message_why: message_why
                    },
                    success: function(response) {
                        if (response === 'Success') {
                            alert('Rent Form declined successfully');
                            location.reload();
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

        function redirectToProductDetails(productId) {
            if (productId) {
                window.location.href = 'admin_product_details.php?product_id=' + productId;
            } else {
                console.error('Product ID is undefined');
            }
        }

        function productmanagement() {
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
                            var editButton = '<a href="#" class="btn btn-primary btn-md card-link editbutton">Edit</a>';
                            var rating = product.rating ? product.rating : 0;
                            var imgsrc = `api/viewproductImgAPI.php?product_id=${encodeURIComponent(productId)}`;
                            var delButton = '<a href="#" class="btn btn-danger btn-md card-link deletebutton"><i class="fa-solid fa-trash"></i></a>'

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
                                '               <a href="#" class="btn btn-primary btn-md card-link" id="morebutton">View</a>' +
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
                        alert('Product details updated successfully');
                        $('#editProductModal').modal('hide');
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert('Failed to update product details');
                    }
                },
                error: function() {
                    alert('Error during the AJAX request');
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



            $('#saveNewProduct').on('click', function() {
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
                                alert('Product added successfully');
                                $('#editProductModal').modal('hide');
                                location.reload(); // Reload the page to reflect the changes
                            } else {
                                alert('Failed to add Product: ' + parsedResponse.message);
                            }
                        } catch (e) {
                            alert('Failed to parse the response: ' + e.message);
                        }
                    },
                    error: function() {
                        alert('Error during the AJAX request');
                    }
                });
            });
        }

        function deleteProduct(product_id){
    const confirmDelete = confirm("Are you sure you want to delete this product?");
    if (confirmDelete) {
        $.ajax({
            url: 'api/delProductAPI.php',
            method: 'POST',
            data: {
                product_id: product_id
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    alert('Product deleted successfully.');
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert('Failed to delete product: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Error occurred while deleting the product: " + error);
            }
        });
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


        $(document).ready(function() {
            // Handle the "Save changes" button click in the Hour Extension modal
            $('#newHours').on('input', function() {
                var additionalHours = $(this).val();
                var productId = $('#extensionProductId').val();

                if (additionalHours && productId) {
                    $.ajax({
                        url: 'api/calculateNewCostAndEndDate.php', // This should be your endpoint to calculate the new cost and end date
                        method: 'POST',
                        data: {
                            product_id: productId,
                            additional_hours: additionalHours
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                // Update the UI with the new values
                                $('#totalHours').text(data.totalHours + ' Hrs');
                                $('#endDate').text(data.new_end_date);
                                $('#totalCost').text(data.new_cost + ' Kyats');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("Failed to calculate new end date and cost: " + error);
                        }
                    });
                }
            });



            // Save the hour extension
            $('#saveExtension').on('click', function() {
                var additionalHours = $('#newHours').val();
                var productId = $('#extensionProductId').val();

                if (additionalHours && productId) {
                    $.ajax({
                        url: 'api/extendHoursAPI.php',
                        method: 'POST',
                        data: {
                            product_id: productId,
                            additional_hours: additionalHours
                        },
                        success: function(response) {
                            if (response === 'Success') {
                                $('#extensionModal').modal('hide');
                                alert("Hour extension requested successfully!");
                                location.reload();
                            } else {
                                alert("Something wrong!")
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("Failed to request hour extension: " + error);
                        }
                    });
                } else {
                    alert("Please provide the number of hours.");
                }
            });

            function filterRentStatus(filterType, filterValue) {
                var filterData = {};
                if (filterType && filterValue) {
                    filterData[filterType] = filterValue;
                }

                $.ajax({
                    url: 'api/filterRentStatusAPI.php',
                    type: 'POST',
                    data: {
                        filters: filterData
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        window.scrollTo(0, 0);
                        displayFilterResults(data);
                    },
                    error: function(error) {
                        console.log('Error during search:', error);
                    }
                });
            }


            function displayFilterResults(data) {
                var displayResultsContainer = $("#displayFilterResult");
                displayResultsContainer.empty();

                var resultProductsSection = $('<div class="d-block d-md-block d-lg-block"></div>');


                $.each(data, function(index, history) {
                    var cancelButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-danger btn-md card-link cancelbutton" >Cancel</a>' : '';
                    var whyButton = history.rent_status === "declined" ?
                        '<a href="#" class="btn btn-primary btn-md card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';

                    var extensionButton = (history.rent_status === "approved / ongoing") ? '<a href="#" class="btn btn-primary btn-md card-link extensionbutton" data-bs-toggle="modal" data-bs-target="#extensionModal"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                    var rentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a href="#" class="btn btn-primary btn-md card-link rentbutton" ><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

                    var scancelButton = history.rent_status === "pending" ? '<a href="#" class="btn btn-danger btn-sm card-link cancelbutton">Cancel</a>' : '';
                    var swhyButton = history.rent_status === "declined" ?
                        '<a href="#" class="btn btn-primary btn-sm card-link whybutton" data-bs-toggle="modal" data-bs-target="#whyModal"><i class="fa-regular fa-message"></i> Why?</a>' : '';
                    var sextensionButton = (history.rent_status === "approved / ongoing") ? '<a href="#" class="btn btn-primary btn-sm card-link extensionbutton" data-bs-toggle="modal" data-bs-target="#extensionModal"><i class="fa-solid fa-clock"></i> Extension</a>' : '';
                    var srentButton = (history.rent_status === "finished" && history.is_active == 1) ? '<a href="#" class="btn btn-primary btn-sm card-link rentbutton"><i class="fa-solid fa-rotate-right"></i> Rent</a>' : '';

                    var resultHistoryCard = $(
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
                        '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
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
                        '               <div class="d-flex justify-content-center align-items-center">' + // Stack buttons on small screens
                        '                   <a href="#" class="btn btn-outline-primary btn-sm card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                        scancelButton +
                        swhyButton +
                        srentButton +
                        sextensionButton +
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
                        '               <p class="mt-2">Start Date : ' + history.start_date + '</p>' +
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
                        '                           <a href="#" class="btn btn-outline-primary btn-md card-link mb-2 mb-md-0 viewbutton"><i class="fa-solid fa-eye"></i> Product</a>' +
                        cancelButton +
                        whyButton +
                        rentButton +
                        extensionButton +
                        '                       </div>' +
                        '                   </div>' +
                        '               </div>' +
                        '           </div>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>'
                    );

                    // Handling the click events
                    resultHistoryCard.find('.viewbutton').on('click', function() {
                        redirectToProductDetails(history.product_id);
                    });

                    resultHistoryCard.find('.cancelbutton').on('click', function() {
                        cancelRentForm(history.product_id);
                    });

                    // Update the click event for the #whybutton to show the modal
                    resultHistoryCard.find('.whybutton').on('click', function() {
                        // If the decline reason is available, set it in the modal
                        var declineReason = history.message_why || "No specific reason provided."; // Replace with actual reason
                        $('#whyModal .modal-body #declineReason').text(declineReason);
                    });

                    resultHistoryCard.find('.rentbutton').on('click', function() {
                        redirectToRentForm(history.product_id);
                    });

                    resultHistoryCard.find('.extensionbutton').on('click', function() {
                        $('#extensionProductId').val(history.product_id);
                        $('#totalHours').text(history.renting_hours + ' Hrs');
                        $('#endDate').text(history.end_date); // Show the current end date
                        $('#totalCost').text(history.total_cost + ' Kyats'); // Show the current total cost
                    });

                    displayResultsContainer.append(resultHistoryCard);
                });
                displayResultsContainer.append(resultProductsSection);
            }



            function redirectToRentForm(productId) {
                if (productId) {
                    window.location.href = 'rentform.php?product_id=' + productId;
                } else {
                    console.error('Product ID is undefined');
                }
            }


        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>