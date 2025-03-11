<?php
include("../includes/header.php");
include("../includes/navbar_admin.php");

//query
$query = "SELECT r.req_number, r.date, u.fullname AS requester_name, u.department, si.item, r.qty, r.status 
          FROM request r 
          JOIN stock_in si ON r.stockin_id = si.stockin_id 
          JOIN users u ON r.user_id = u.user_id 
          WHERE r.status IN (1, 2)  -- 
          ORDER BY r.date DESC"; 
$result = mysqli_query($conn, $query);
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <?php
        include("../includes/topbar.php");
        ?>

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Issuance History</h1>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable table pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Requisition #</th>
                                    <th>Requester</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['req_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td>
                                            <?php
                                            // Display status based on the value
                                            if ($row['status'] == 1) {
                                                echo '<span class="badge badge-success">Served</span>';
                                            } elseif ($row['status'] == 2) {
                                                echo '<span class="badge badge-danger">Declined</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#viewIssuanceModal">
                                                <i class="fa-solid fa-eye text-white"></i>
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
</div>