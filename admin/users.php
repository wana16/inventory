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

        // query to select only visible users
        $query = "SELECT * FROM users WHERE is_hide = 0";
        $result = mysqli_query($conn, $query);
        ?>


        <!-- ADD MODAL-->
        <div class="modal fade" id="GMCadduser" tabindex="-1" role="dialog" aria-labelledby="ItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemModalLabel">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="create.php" method="POST">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Full Name</label>
                                    <input type="text" name="fullname" class="form-control" required>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="password" name="pword" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Phone Number</label>
                                    <input type="text" name="number" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Department</label>
                                    <input type="text" name="department" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" id="roleSelect" required class="form-control" onchange="toggleBranchField(this)">
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="mmo">MMO</option>
                                    <option value="engineering">Engineering</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="adduser" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end of add modal -->

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="user_hide.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="user_id" id="deleteUserId">
                            Are you sure you want to hide this user?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Hide</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Users</h1>
                <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#GMCadduser">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add
                </button>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="card-datatable table pt-0">
                        <table class="datatables-basic table" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Department</th>
                                    <th>User Role</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['fullname']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['department']; ?></td>
                                        <td>
                                            <?php
                                            switch ($row['role']) {
                                                case 'admin':
                                                    echo 'Admin';
                                                    break;
                                                case 'mmo':
                                                    echo 'MMO';
                                                    break;
                                                case 'engineering':
                                                    echo 'Engineering';
                                                    break;
                                                case 'user':
                                                    echo 'User';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-success editproduct-btn"><i class="fa-solid fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['user_id']; ?>" data-toggle="modal" data-target="#deleteConfirmationModal">
                                                <i class="fa-solid fa-trash text-white"></i>
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
        $(document).ready(function() {
            $(".delete-btn").click(function() {
                var userId = $(this).data("id");
                $("#deleteUserId").val(userId);
            });
        });
    </script>