<?php
include 'db.php';
include 'maindash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>

    <!-- Include Bootstrap, DataTables, and Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include jQuery and necessary scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        /* Style for Select2 dropdown */
        .select2-container {
            width: 100% !important;
        }
        .select2-selection {
            height: 38px !important; 
            padding: 6px !important;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Branch Sales Report</h2>

    <div class="mb-3">
        <label for="branchSelect" class="form-label">Select Branch:</label>
        <select id="branchSelect" class="form-control select2">
            <option value="">-- Select Branch --</option>
            <?php
                include 'db.php';  // Database connection
                $query = "SELECT id, branch_name FROM branches";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['branch_name']}</option>";
                }
            ?>
        </select>
    </div>

    <h4>Total Sales: ₹<span id="totalSales">0</span></h4>

    <table id="salesTable" class="display table table-bordered">
        <thead>
            <tr>
                <th>Reference No</th>
                <th>Sender</th>
                <th>Recipient</th>
                <th>Weight (kg)</th>
                <th>Price (₹)</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    var table = $('#salesTable').DataTable({
        dom: 'Bfrtip', // Show buttons for export
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                className: 'btn btn-success',
                filename: function () {
                    return 'sales_report_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '');
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                className: 'btn btn-warning',
                filename: function () {
                    return 'sales_report_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '');
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'btn btn-danger',
                filename: function () {
                    return 'salesreport_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '');
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-primary'
            }
        ],
        responsive: true
    });

    $('#branchSelect').change(function() {
        var branchId = $(this).val();
        if (branchId) {
            $.ajax({
                url: 'fetch_sales_report.php',
                type: 'POST',
                data: { branch_id: branchId },
                dataType: 'json',
                success: function(response) {
                    table.clear().rows.add(response.data).draw();
                    $('#totalSales').text(response.total_sales);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        } else {
            table.clear().draw();
            $('#totalSales').text('₹0');
        }
    });
});
</script>

</body>
</html>
