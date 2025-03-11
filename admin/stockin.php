<?php
include("../includes/header.php");
include("../includes/navbar_admin.php");

// Fetch the last control number from the database
$lastControlNoQuery = "SELECT controlNO FROM stock_in ORDER BY stockin_id DESC LIMIT 1";
$lastControlNoResult = mysqli_query($conn, $lastControlNoQuery);
$lastControlNo = mysqli_fetch_assoc($lastControlNoResult);
$nextControlNo = isset($lastControlNo['controlNO']) ? intval(substr($lastControlNo['controlNO'], 3)) + 1 : 1; // Increment the last number
$controlNumber = 'CN-' . $nextControlNo;

//query
$query = "SELECT * FROM stock_in WHERE stockin_id IN 
         (SELECT MIN(stockin_id) FROM stock_in GROUP BY controlNO) ORDER BY stockin_id DESC";
$result = mysqli_query($conn, $query);

?>

<style>
    .modal-body {
        padding: 20px;
    }

    .table {
        font-size: 14px;
    }
</style>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- topbar -->
        <?php
        include("../includes/topbar.php");

        ?>

        <!-- ADD MODAL -->
        <div class="modal fade" id="GMCaddStockin" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemModalLabel">Add Stock-in</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="create.php" method="POST">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #76a73c;">
                                    <strong>Stock-in Items</strong>
                                </div>
                                <div class="card-body">
                                    <div id="itemFields">
                                        <div class="form-row item-row mb-3">
                                            <div class="form-group col-md-6 col-12">
                                                <label>Item</label>
                                                <input type="text" name="item[]" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>Quantity</label>
                                                <input type="number" name="qty[]" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="warranty1" name="warranty[]" value="1">
                                                    <label class="form-check-label" for="warranty1">With Warranty?</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <button type="button" class="btn btn-sm btn-secondary" id="addItem">Add Item</button>
                            </div>

                            <hr>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-12">
                                    <label>Control Number</label>
                                    <input type="text" name="controlNO" class="form-control" value="<?php echo $controlNumber; ?>" readonly>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label for="category">Category</label>
                                    <select class="custom-select" id="cat_id" name="category" aria-label="Default select example" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="IT Equipment">IT Equipment</option>
                                        <option value="Engineering Equipment">Engineering Equipment</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-12">
                                    <label>Date of Purchase</label>
                                    <input type="date" name="dop" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>Date Received</label>
                                    <input type="date" name="dr" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="addStockin" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end of add modal -->

        <!-- View Product Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewStockinModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewStockinModalLabel">Stock-in Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Control #</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Category</th>
                                        <th>Date of Purchase</th>
                                        <th>Date Received</th>
                                        <th>Warranty</th>
                                    </tr>
                                </thead>
                                <tbody id="stockinDetailsBody">
                                    <!-- Dynamic content will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of view modal-->


        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmPostModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Post</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to finalize this stock-in? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmPostBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addItem').addEventListener('click', function() {
                    const itemFields = document.getElementById('itemFields');

                    const newItemRow = document.createElement('div');
                    newItemRow.classList.add('form-row', 'item-row', 'mb-3');

                    newItemRow.innerHTML = `
                        <div class="form-group col-md-6 col-12">
                            <label>Item</label>
                            <input type="text" name="item[]" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Quantity</label>
                            <input type="number" name="qty[]" class="form-control" required>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="warranty[]" value="1">
                                <label class="form-check-label">With Warranty?</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm removeItem">X</button>
                    `;

                    itemFields.appendChild(newItemRow);

                    newItemRow.querySelector('.removeItem').addEventListener('click', function() {
                        itemFields.removeChild(newItemRow); // Remove the item row
                    });
                });

                // Update view modal functionality
                $('.view-btn').on('click', function() {
                    const controlno = $(this).data('controlno');

                    // ajax para i fetch ang details sa stockin sa view modal
                    $.ajax({
                        url: 'fetch_stockin_details.php',
                        type: 'POST',
                        data: {
                            controlNO: controlno
                        },
                        success: function(data) {
                            $('#stockinDetailsBody').html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching stock-in details: ", error);
                        }
                    });
                });
                
                $(document).ready(function() {
                    $("#postStockBtn").click(function() {
                        $("#confirmPostModal").modal("show");
                    });

                    $("#confirmPostBtn").click(function() {
                        window.location.href = "post_stockin.php";
                    });
                });

                // Handle post button click
                document.querySelectorAll('#postStockBtn').forEach(button => {
                    button.addEventListener('click', function() {
                        const stockinId = this.getAttribute('data-stockin-id');
                        // Show confirmation modal
                        $('#confirmPostModal').modal('show');

                        // Confirm post action
                        document.getElementById('confirmPostBtn').onclick = function() {
                            window.location.href = `post_stockin.php?stockin_id=${stockinId}`;
                        };
                    });
                });
            });
        </script>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock-in</h1>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#GMCaddStockin">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add Stock-in
                </button>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Control #</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th>Date Received</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['controlNO']; ?></td>
                                        <td><?php echo $row['item']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['qty']; ?></td>
                                        <td><?php echo $row['dr']; ?></td>
                                        <td>
                                            <?php if ($row['is_posted'] == 0): // Check if not posted 
                                            ?>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#GMCeditStockin" class="btn btn-sm btn-success edit-btn"><i class="fa-solid fa-edit"></i></button>
                                            <?php endif; ?>
                                            <button type="button" data-toggle="modal" data-target="#viewModal" class="btn btn-sm btn-warning view-btn" data-controlno="<?php echo htmlspecialchars($row['controlNO']); ?>">
                                                <i class="fa-solid fa-eye text-white"></i>
                                            </button>
                                            <?php if ($row['is_posted'] == 0): // Check if not posted 
                                            ?>
                                                <button type="button" class="btn btn-sm btn-info" id="postStockBtn" data-stockin-id="<?php echo $row['stockin_id']; ?>">
                                                    <i class="fas fa-square-check"></i>
                                                </button>
                                              <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->


    </div>
    <!-- End of Main Content -->

    <?php
    include("../includes/scripts.php");
    include("../includes/footer.php");
    include("../includes/datatables.php");
    ?>