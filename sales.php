<?php
include 'db.php';
include 'maindash.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Sales Report</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
<body class="bg-light">

<div class="dashboard-content">
    <div class="container">
        <h2 class="text-center mb-4">Branch Sales Report</h2>

        <div class="card shadow p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-12">
                    <label for="branch" class="form-label">Select Branch:</label>
                    <select id="branch" name="branch" class="form-select" onchange="fetchReport()">
                        <option value="">-- Select Branch --</option>
                    </select>
                </div>
            </div>
        </div>

        <h3 class="mt-4">Sales Report</h3>
        <div class="card p-3 shadow">
            <table id="salesReport" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Branch Name</th>
                        <th>Total Parcels</th>
                        <th>Total Revenue</th>
                        <th>Delivered</th>
                        <th>In-Transit</th>
                        <th>Pending</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Load branch names into the dropdown
    $.getJSON("fet_branches.php", function(data) {
        $.each(data, function(index, branch) {
            $('#branch').append('<option value="' + branch.id + '">' + branch.name + '</option>');
        });
    });

    // Initialize DataTable
    $('#salesReport').DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "info": true
    });
});

function fetchReport() {
    var branchId = $('#branch').val();
    if (branchId == "") {
        return; // Do nothing if no branch is selected
    }

    $.ajax({
        url: "fetch_sales.php",
        type: "POST",
        data: { branch_id: branchId },
        dataType: "json",
        success: function(response) {
            console.log("Server Response:", response); // Debugging

            if (response.error) {
                alert(response.error);
                return;
            }

            var table = $('#salesReport').DataTable();
            table.clear().draw();

            table.row.add([
                response.branch_name,
                response.total_parcels,
                "₹" + parseFloat(response.total_revenue).toFixed(2),
                response.delivered_count,
                response.in_transit_count,
                response.pending_count
            ]).draw(false);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching report:", xhr.responseText); // Debugging
            alert("Failed to fetch data. Check console.");
        }
    });
}
</script>

</body>
</html>
