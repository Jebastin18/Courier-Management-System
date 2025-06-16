<?php
include 'db.php';
include 'maindash.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        .dashboard-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease-in-out;
        }
        @media (max-width: 992px) {
            .dashboard-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-content">
    <div class="container">
        <h2 class="text-center mb-4">User Report</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">User Details</div>
            <div class="card-body">
                <table id="reportTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users"; // Change table name as per your DB
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $statusText = ($row['status'] == 'active') ? "Active" : "Inactive";
                                $buttonClass = ($row['status'] == 'active') ? "btn-success" : "btn-danger";
                                $newStatus = ($row['status'] == 'active') ? 'inactive' : 'active';

                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['phone_number']}</td>
                                    <td>{$row['role']}</td>
                                    <td id='status_{$row['id']}'>$statusText</td>
                                    <td>
                                        <button class='btn $buttonClass toggle-status' data-id='{$row['id']}' data-status='$statusText'>
                                            $statusText
                                        </button>
                                    </td>
                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#reportTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });

    // Toggle user status (Active/Inactive)
    $(".toggle-status").click(function() {
        let button = $(this);
        let userId = button.data("id");
        let currentStatus = button.data("status"); 
        let newStatus = (currentStatus === "Active") ? "inactive" : "active";

        $.ajax({
            url: "update_status.php",
            type: "POST",
            data: { user_id: userId, status: newStatus },
            success: function(response) {
                if (response == "success") {
                    let statusText = (newStatus === "active") ? "Active" : "Inactive";
                    let buttonClass = (newStatus === "active") ? "btn-success" : "btn-danger";

                    $("#status_" + userId).text(statusText);
                    button.text(statusText);
                    button.removeClass("btn-success btn-danger").addClass(buttonClass);
                    button.data("status", statusText);
                } else {
                    alert("Failed to update status.");
                }
            }
        });
    });
});
</script>

</body>
</html>
