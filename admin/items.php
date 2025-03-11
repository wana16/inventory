<?php
include("../includes/header.php");
include("../includes/navbar.php");
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- topbar -->
        <?php
        include("../includes/topbar.php");

        // query
        $query = "SELECT items.*, categories.cat_name FROM items 
                  LEFT JOIN categories ON items.cat_id = categories.cat_id";
        $result = mysqli_query($conn, $query);   
        ?>


        <!-- ADD MODAL-->
        <div class="modal fade" id="GMCadditem" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemModalLabel">Add New Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="create.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Item</label>
                                <input type="text" name="item_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <select class="custom-select" id="cat_id" name="cat_id" aria-label="Default select example">
                                    <option value="" disabled selected>Select Category</option>
                                    <?php
                                    $categories = mysqli_query( $conn, "SELECT * FROM categories");
                                    while ($cat = mysqli_fetch_array($categories)) {
                                    ?>
                                    <option value="<?php echo $cat['cat_id'] ?>"><?php echo $cat['cat_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="addItem" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end of add modal -->


        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Items</h1>
                <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#GMCadditem">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add
                </button>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                
                                <tr>
                                    <td><?php echo $row ['id']; ?></td>
                                    <td><?php echo $row ['item_name']; ?></td>
                                    <td><?php echo $row ['cat_name']; ?></td>
                                    <td><?php echo $row ['description']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-link text-primary" data-toggle="modal" data-target="#edit-modal">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button class="btn btn-link text-danger" data-toggle="modal" data-target="#delete-modal">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
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
    ?>