<?php
include 'db.php';
include 'maindash.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

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
        <h2 class="text-center mb-4">Parcel Report</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Parcel Details</div>
            <div class="card-body">
                <table id="parcelTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Parcel ID</th>
                            <th>Tracking Number</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>From Branch</th>
                            <th>To Branch</th>
                            <th>Total Amount</th>
                            <th>Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch Parcel Data with Branch Information
                        $sql = "SELECT p.id, p.tracking_number, p.sender_name, p.receiver_name, p.total_amount, 
                                       b_from.branch_name AS from_branch, b_to.branch_name AS to_branch
                                FROM parcels p
                                LEFT JOIN branches b_from ON p.from_branch_id = b_from.id
                                LEFT JOIN branches b_to ON p.to_branch_id = b_to.id
                                ORDER BY p.id DESC";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $parcel_id = $row['id'];

                                // Fetch Items for this Parcel
                                $itemSql = "SELECT item_name, kilograms, price FROM parcel_items WHERE parcel_id = $parcel_id";
                                $itemResult = $conn->query($itemSql);

                                $items = "";
                                while ($itemRow = $itemResult->fetch_assoc()) {
                                    $items .= "{$itemRow['item_name']} ({$itemRow['kilograms']}kg) - ₹{$itemRow['price']}<br>";
                                }

                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['tracking_number']}</td>
                                    <td>{$row['sender_name']}</td>
                                    <td>{$row['receiver_name']}</td>
                                    <td>{$row['from_branch']}</td>
                                    <td>{$row['to_branch']}</td>
                                    <td>₹{$row['total_amount']}</td>
                                    <td>$items</td>
                                    <td>
                                        <a href='edit_parcel.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center'>No parcels found</td></tr>";
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
    $('#parcelTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>

</body>
</html>
