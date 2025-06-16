<?php
include 'db.php';
include 'maindash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Reports</title>
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
        <h2 class="text-center mb-4">Branch Report</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Branch Details</div>
            <div class="card-body">
                <table id="branchReportTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch Code</th>
                            <th>Branch Name</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Pincode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, branch_code, branch_name, city, phone_number, address, pincode FROM branches";
                        $result = $conn->query($sql);
                        if (!$result) {
                            die("Query failed: " . $conn->error); 
                        }
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['branch_code']}</td>
                                    <td>{$row['branch_name']}</td>
                                    <td>{$row['city']}</td>
                                    <td>{$row['phone_number']}</td>
                                    <td>{$row['address']}</td>
                                    <td>{$row['pincode']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
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
    $('#branchReportTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });
});
</script>
</body>
</html>
