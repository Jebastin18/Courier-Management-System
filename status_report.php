<?php
include 'db.php';
include 'maindash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Status Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        <h2 class="text-center mb-4">Parcel Status Report</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Parcel Status Details</div>
            <div class="card-body">
                <table id="parcelTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th> 
                            <th>Tracking Number</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#parcelTable').DataTable({
        "ajax": "fetch_status.php",
        "columns": [
            { "data": null }, 
            { "data": "tracking_number" },
            { "data": "sender_name" },
            { "data": "receiver_name" },
            { "data": "status" }
        ],
        "columnDefs": [
            {
                "targets": 0,
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            }
        ],
        "responsive": true,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });
});
</script>
</body>
</html>
