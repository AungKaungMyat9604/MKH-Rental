<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="declineModalLabel">Reason for Decline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="message_why" class="form-label">Message for Customer: </label>
                            <input type="text" class="form-control" id="message_why">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="uploadMessage">Upload</button>
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="whyModal" tabindex="-1" aria-labelledby="whyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="whyModalLabel">Reason for Decline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- This is where you can dynamically insert the reason for the decline -->
                        <p id="declineReason">The reason for the decline will be displayed here.</p>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for View Customer -->
        <div class="modal fade" id="viewCusModal" tabindex="-1" aria-labelledby="viewCusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCusModalLabel">Customer Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id" value="">
                        <img id="profilePic" src="api/viewprofilepictureAPI.php?user_id=" alt="Click to Upload" class="text-center rounded-circle img-fluid mx-auto d-block" style="background-color: white; width: 100px; height: 100px; object-fit: cover;">
                        <div class="mb-3 mt-3">
                            <label class="form-label">Full Name: <span id="fullname"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email: <span id="email"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone: <span id="phone"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City: <span id="city"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Township: <span id="township"></span></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Product Edit -->
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
                            <a  class="btn btn-primary" id="uploadpic" onclick="triggerFileInput()">Upload</a>
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
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control" id="type" name="type" required>
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

        <!-- Modal for Hour Extension -->
        <div class="modal fade" id="extensionModal" tabindex="-1" aria-labelledby="extensionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="extensionModalLabel">Hour Extension</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="extensionProductId" value="">
                        <div class="mb-3">
                            <label for="newHours" class="form-label">Additional Hours: </label>
                            <input type="number" class="form-control" id="newHours" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Hours: <span id="totalHours"></span></label>
                            <!-- This will display the current or updated end date -->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date: <span id="endDate"></span></label>
                            <!-- This will display the current or updated end date -->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Cost: <span id="totalCost"></span></label>
                            <!-- This will display the current or updated total cost -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveExtension">Upload</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Viewing Location -->
        <div class="modal fade" id="viewLocationModal" tabindex="-1" aria-labelledby="viewLocationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewLocationModalLabel">Selected Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="adminMap" style="width: 100%; height: 400px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <a href="#" id="openInGoogleMaps" class="btn btn-primary">Open in Google Maps</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Adding Product -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addProductForm" enctype="multipart/form-data">
                            <div class="img-container mb-1 d-flex justify-content-start">
                                <img id="product_image_preview_add" class="card-img-top" alt="New Product Image" style="width: 200px; height: auto; object-fit: cover; border: 1px solid black;">
                            </div>
                            <a href="#" class="btn btn-primary" id="uploadpicAdd" onclick="triggerFileInputAdd()">Upload</a>
                            <input type="file" name="file" id="fileAdd" style="display: none;" onchange="handleFileInputChangeAdd()" required>

                            <div class="mb-3">
                                <label for="product_name_add" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name_add" name="product_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="product_type_add" class="form-label">Product Type</label>
                                <select class="form-select" id="product_type_add" name="product_type" required>
                                    <option value="" disabled selected>Select Product Type</option>
                                    <option value="Excavator">Excavator</option>
                                    <option value="Attachment">Attachment</option>
                                    <option value="Truck">Truck</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cost_per_unit_add" class="form-label">Cost Per Unit (1 Duty = 8 hrs)</label>
                                <input type="number" class="form-control" id="cost_per_unit_add" name="cost_per_unit" required>
                            </div>

                            <div class="mb-3">
                                <label for="type_add" class="form-label">Type</label>
                                <input type="text" class="form-control" id="type_add" name="type" required>
                            </div>

                            <div class="mb-3">
                                <label for="brand_add" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand_add" name="brand">
                            </div>

                            <!-- Conditional Fields -->
                            <div id="excavatorFieldsAdd" style="display:none;">
                                <div class="mb-3">
                                    <label for="ton_add" class="form-label">Ton</label>
                                    <input type="number" class="form-control" id="ton_add" name="ton">
                                </div>
                                <div class="mb-3">
                                    <label for="arm_length_add" class="form-label">Arm Length (m)</label>
                                    <input type="text" class="form-control" id="arm_length_add" name="arm_length">
                                </div>
                                <div class="mb-3">
                                    <label for="bucket_size_add" class="form-label">Bucket Size (m<sup>3</sup>)</label>
                                    <input type="text" class="form-control" id="bucket_size_add" name="bucket_size">
                                </div>
                                <div class="mb-3">
                                    <label for="dimension_add" class="form-label">Dimension (m)</label>
                                    <input type="text" class="form-control" id="dimension_add" name="dimension">
                                </div>
                                <div class="mb-3">
                                    <label for="fuel_tank_capacity_add" class="form-label">Fuel Tank Capacity (l)</label>
                                    <input type="text" class="form-control" id="fuel_tank_capacity_add" name="fuel_tank_capacity">
                                </div>
                            </div>

                            <div id="attachmentFieldsAdd" style="display:none;">
                                <div class="mb-3">
                                    <label for="compatible_excavator_add" class="form-label">Compatible Excavator</label>
                                    <input type="text" class="form-control" id="compatible_excavator_add" name="compatible_excavator">
                                </div>
                            </div>

                            <div id="generalFieldsAdd">
                                <div class="mb-3">
                                    <label for="weight_add" class="form-label">Weight (kg)</label>
                                    <input type="text" class="form-control" id="weight_add" name="weight">
                                </div>
                                <div class="mb-3">
                                    <label for="usage_conditions_add" class="form-label">Usage Conditions</label>
                                    <textarea class="form-control" id="usage_conditions_add" name="usage_conditions" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- End Conditional Fields -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveNewProduct">Add Product</button>
                    </div>
                </div>
            </div>
        </div>