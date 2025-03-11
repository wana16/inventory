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
        $query = "SELECT * FROM stockin";
        $result = mysqli_query($conn, $query);   
        ?>




        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Items</h1>
                
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