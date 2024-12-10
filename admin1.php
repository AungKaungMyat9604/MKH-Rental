<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#rentManagement">
                                Rent Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#productManagement">
                                Product Management
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <!-- Rent Management Section -->
                <section id="rentManagement" class="my-4">
                    <h2>Rent Management</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rent ID</th>
                                <th>Product Name</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rentTableBody">
                            <!-- Rent rows will be dynamically populated here -->
                        </tbody>
                    </table>
                </section>

                <!-- Product Management Section -->
                <section id="productManagement" class="my-4">
                    <h2>Product Management</h2>
                    <button class="btn btn-primary mb-3" id="addProductBtn">Add New Product</button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Type</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <!-- Product rows will be dynamically populated here -->
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>

    <!-- Modals for actions like Add/Edit Product and Manage Rent -->
    <!-- Example: Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add/Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId" name="productId">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productType" class="form-label">Product Type</label>
                            <input type="text" class="form-control" id="productType" name="productType" required>
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            loadRentManagement();
            loadProductManagement();

            // Load Rent Management data
            function loadRentManagement() {
                $.ajax({
                    url: 'api/getRentData.php',
                    method: 'GET',
                    success: function(data) {
                        let rentRows = '';
                        $.each(data, function(index, rent) {
                            rentRows += `<tr>
                                <td>${rent.rent_id}</td>
                                <td>${rent.product_name}</td>
                                <td>${rent.user_name}</td>
                                <td>${rent.status}</td>
                                <td>${rent.start_date}</td>
                                <td>${rent.end_date}</td>
                                <td>
                                    <button class="btn btn-success btn-sm approveBtn" data-id="${rent.rent_id}">Approve</button>
                                    <button class="btn btn-danger btn-sm declineBtn" data-id="${rent.rent_id}">Decline</button>
                                </td>
                            </tr>`;
                        });
                        $('#rentTableBody').html(rentRows);
                    }
                });
            }

            // Load Product Management data
            function loadProductManagement() {
                $.ajax({
                    url: 'api/getProductData.php',
                    method: 'GET',
                    success: function(data) {
                        let productRows = '';
                        $.each(data, function(index, product) {
                            productRows += `<tr>
                                <td>${product.product_id}</td>
                                <td>${product.product_name}</td>
                                <td>${product.type}</td>
                                <td>${product.brand}</td>
                                <td>${product.status}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm editBtn" data-id="${product.product_id}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${product.product_id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                        $('#productTableBody').html(productRows);
                    }
                });
            }

            // Handle Add/Edit Product Button Click
            $('#addProductBtn, .editBtn').on('click', function() {
                const productId = $(this).data('id');
                if (productId) {
                    // Load product data into the modal
                    $.ajax({
                        url: 'api/getProductDetails.php',
                        method: 'GET',
                        data: { product_id: productId },
                        success: function(data) {
                            $('#productId').val(data.product_id);
                            $('#productName').val(data.product_name);
                            $('#productType').val(data.type);
                            $('#brand').val(data.brand);
                            $('#status').val(data.status);
                            $('#productModalLabel').text('Edit Product');
                            $('#productModal').modal('show');
                        }
                    });
                } else {
                    $('#productForm')[0].reset();
                    $('#productModalLabel').text('Add Product');
                    $('#productModal').modal('show');
                }
            });

            // Handle Save Product Button Click
            $('#productForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'api/saveProduct.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#productModal').modal('hide');
                        loadProductManagement();
                    }
                });
            });

            // Handle Delete Product Button Click
            $('.deleteBtn').on('click', function() {
                const productId = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: 'api/deleteProduct.php',
                        method: 'POST',
                        data: { product_id: productId },
                        success: function(data) {
                            loadProductManagement();
                        }
                    });
                }
            });

            // Handle Approve/Decline Rent Button Clicks
            $('.approveBtn, .declineBtn').on('click', function() {
                const rentId = $(this).data('id');
                const action = $(this).hasClass('approveBtn') ? 'approve' : 'decline';
                $.ajax({
                    url: 'api/updateRentStatus.php',
                    method: 'POST',
                    data: { rent_id: rentId, action: action },
                    success: function(data) {
                        loadRentManagement();
                    }
                });
            });
        });
    </script>
</body>
</html>
