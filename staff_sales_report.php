<?php
include 'db.php';
include 'maindash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch & Staff Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
    <div class="card shadow p-3 mt-4">
        <h4>Staff Sales Report</h4>
        <div class="row g-3 align-items-center">
            <div class="col-md-12">
                <label for="staff" class="form-label">Select Staff:</label>
                <select id="staff" name="staff" class="form-select" onchange="fetchStaffReport()">
                    <option value="">-- Select Staff --</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card p-3 shadow mt-3">
        <h4>Selected Staff</h4>
        <p><strong>Staff Name:</strong> <span id="staffName"></span></p>
        <p><strong>Branch Name:</strong> <span id="branchCode"></span></p>
        <p><strong>Total Sales:</strong> <span id="totalSales"></span></p>
    </div>
    <div class="card p-3 shadow mt-3">
        <h4>Staff Sales Details</h4>
        <table id="staffSalesReport" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Tracking No</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Price (₹)</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function(){
    $.getJSON("fetch_staff.php", function(data) {
        $.each(data, function(index, staff) {
            $('#staff').append('<option value="' + staff.id + '">' + staff.name + '</option>');
        });
    });
    $('#staffSalesReport').DataTable();
});
function fetchStaffReport() {
    var staffId = $('#staff').val();
    if (staffId == "") return;
    $.ajax({
        url: "fetch_staff_sales.php",
        type: "POST",
        data: { staff_id: staffId },
        dataType: "json",
        success: function(response) {
            $('#staffName').text(response.staff_name);
            $('#branchCode').text(response.branch_name);
            $('#totalSales').text("₹" + parseFloat(response.total_sales).toFixed(2));
            var table = $('#staffSalesReport').DataTable();
            table.clear().draw();
            $.each(response.sales, function(index, sale) {
                table.row.add([
                    sale.tracking_number,
                    sale.sender,
                    sale.receiver,
                    "₹" + parseFloat(sale.price).toFixed(2),
                    sale.date_created
                ]).draw(false);
            });
        },
        error: function(xhr) {
            console.error("Error fetching staff report:", xhr.responseText);
            alert("Failed to fetch staff report.");
        }
    });
}
</script>
</body>
</html>
