<?php
include("../includes/header.php");
include("../includes/navbar_admin.php");
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- topbar -->
        <?php
        include("../includes/topbar.php");

        // query
        $query = "SELECT * FROM asset";
        $result = mysqli_query($conn, $query);
        ?>

        <!-- ADD MODAL -->
        <div class="modal fade" id="GMCaddMR" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemModalLabel">Ambot</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="create.php" method="POST">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #76a73c;">
                                    <strong>Stock-in items</strong>
                                </div>
                                <div class="card-body">
                                    <div id="itemFields">
                                        <div class="form-row item-row">
                                            <div class="form-group col-md-4">
                                                <label>Item</label>
                                                <select name="item[]" class="form-control" required>
                                                    <?php
                                                    // Fetch approved items from the request table and join with stockin table
                                                    $itemQuery = "
                                                        SELECT DISTINCT s.item
                                                        FROM request r 
                                                        JOIN stock_in s ON r.stockin_id = s.stockin_id 
                                                        WHERE r.status = 'approved'
                                                    ";
                                                    $itemResult = mysqli_query($conn, $itemQuery);
                                                    while ($itemRow = mysqli_fetch_assoc($itemResult)): ?>
                                                        <option value="<?php echo htmlspecialchars($itemRow['item']); ?>">
                                                            <?php echo htmlspecialchars($itemRow['item']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Quantity</label>
                                                <input type="text" name="qty[]" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Serial Number</label>
                                                <input type="text" name="serialNO[]" class="form-control" required>
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
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Department</label>
                                    <input type="text" name="department" class="form-control">
                                </div>

                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="addMR" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end of add modal -->

        <!-- View Product Modal -->
        <div class="modal fade" id="viewMRModal" tabindex="-1" role="dialog" aria-labelledby="viewMRModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMRModalLabel">View Fixed Asset Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Name and Description</th>
                                        <th class="text-center">Serial Number</th>
                                    </tr>
                                </thead>
                                <tbody id="view_items_table">
                                    <!-- Items will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <div class="form-group">
                                <label><strong>Owner:</strong></label>
                                <div><span id="view_name"></span></div>
                            </div>
                            <div class="form-group">
                                <label><strong>Department</strong></label>
                                <div><span id="view_ip"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of view modal-->


        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Fixed Assets</h1>
                <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#GMCaddMR">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Create New
                </button>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>User</th>
                                    <th>Department</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['department']; ?></td>
                                        <td>
                                            <button type="button" 
                                                class="btn btn-sm btn-warning fixed-btn"
                                                data-toggle="modal" 
                                                data-target="#viewMRModal"
                                                data-qty='<?php echo htmlspecialchars(json_encode($row['qty']), ENT_QUOTES); ?>'
                                                data-item='<?php echo htmlspecialchars(json_encode($row['item']), ENT_QUOTES); ?>'
                                                data-serialno='<?php echo htmlspecialchars(json_encode($row['serialNO']), ENT_QUOTES); ?>'
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-ip="<?php echo htmlspecialchars($row['department']); ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addItem').addEventListener('click', function() {
                const itemFields = document.getElementById('itemFields');

                // Create a new item row
                const newItemRow = document.createElement('div');
                newItemRow.classList.add('form-row', 'item-row', 'mb-2');

                // Updated HTML with inline remove button
                newItemRow.innerHTML = `
                    <div class="form-group col-md-4">
                                                <label>Item</label>
                                                <select name="item[]" class="form-control" required>
                                                    <?php
                                                    // Fetch approved items from the request table and join with stockin table
                                                    $itemQuery = "
                                                        SELECT DISTINCT s.item
                                                        FROM request r 
                                                        JOIN stock_in s ON r.stockin_id = s.stockin_id 
                                                        WHERE r.status = 'approved'
                                                    ";
                                                    $itemResult = mysqli_query($conn, $itemQuery);
                                                    while ($itemRow = mysqli_fetch_assoc($itemResult)): ?>
                                                        <option value="<?php echo htmlspecialchars($itemRow['item']); ?>">
                                                            <?php echo htmlspecialchars($itemRow['item']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                    <div class="form-group col-md-4">
                        <label>Quantity</label>
                        <input type="text" name="qty[]" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Serial Number</label>
                        <input type="text" name="serialNO[]" class="form-control" required>
                    </div>
                    <div class="form-group col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm removeItem form-control">X</button>
                    </div>
                `;

                itemFields.appendChild(newItemRow);

                newItemRow.querySelector('.removeItem').addEventListener('click', function() {
                    itemFields.removeChild(newItemRow);
                });
            });
            // Update view modal functionality
            $('.fixed-btn').on('click', function() {
                const items = JSON.parse($(this).data('item'));
                const qtys = JSON.parse($(this).data('qty'));
                const serialNOs = JSON.parse($(this).data('serialno'));
                const name = $(this).data('name');
                const department = $(this).data('ip');

                // Populate basic info
                $('#view_name').text(name);
                $('#view_ip').text(department);

                // Create the items table content
                let tableContent = '';
                
                // Check if items is an array
                if (Array.isArray(items)) {
                    for (let i = 0; i < items.length; i++) {
                        tableContent += `
                            <tr>
                                <td>${qtys[i]}</td>
                                <td>${items[i]}</td>
                                <td>${serialNOs[i]}</td>
                            </tr>
                        `;
                    }
                } else {
                    // If it's a single item, split the string (removing brackets and quotes)
                    const itemsArray = items.replace(/[\[\]"]/g, '').split(',');
                    const qtysArray = qtys.replace(/[\[\]"]/g, '').split(',');
                    const serialNOsArray = serialNOs.replace(/[\[\]"]/g, '').split(',');
                    
                    for (let i = 0; i < itemsArray.length; i++) {
                        tableContent += `
                            <tr>
                                <td>${qtysArray[i]}</td>
                                <td>${itemsArray[i]}</td>
                                <td>${serialNOsArray[i]}</td>
                            </tr>
                        `;
                    }
                }
                $('#view_items_table').html(tableContent);
            });
        });
    </script>