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
        $query = "SELECT 
            stock_in.stockin_id,   
            stock_in.item,
            stock_in.category,
            SUM(stock_in.qty) AS available_stock
        FROM stock_in 
        WHERE stock_in.is_posted = 1
        GROUP BY stock_in.stockin_id, stock_in.item, stock_in.category";
        $result = mysqli_query($conn, $query);
        ?>


        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock Overview</h1>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Available Stock</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['item']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['available_stock']; ?></td>
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