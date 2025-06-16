<?php
include 'db.php';
include 'maindash.php';

// Fetch all parcels from the database
$sql = "SELECT id, tracking_number, sender_name, receiver_name, total_amount, status FROM parcels";
$result = $conn->query($sql);

// Handle form submission for bulk update
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['parcel_ids'])) {
    $parcel_ids = $_POST['parcel_ids']; // Array of selected parcel IDs
    $new_status = $_POST['status']; // New status selected

    if (!empty($parcel_ids)) {
        $parcel_ids_str = implode(',', array_map('intval', $parcel_ids));

        // Update status for selected parcels
        $update_sql = "UPDATE parcels SET status = '$new_status' WHERE id IN ($parcel_ids_str)";
        
        if ($conn->query($update_sql) === TRUE) {
            foreach ($parcel_ids as $parcel_id) {
                $history_sql = "INSERT INTO tracking_history (parcel_id, status, updated_at) VALUES ('$parcel_id', '$new_status', NOW())";
                $conn->query($history_sql);
            }

            echo "<script>alert('Selected parcel statuses updated successfully!'); window.location.href='parsel_report.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating parcel statuses');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Multiple Parcel Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .dashboard-content {
            margin-top: 100px;
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
        .sticky-options {
            position: sticky;
            top: 0;
            background: white;
            z-index: 100;
            padding: 30px 0;
        }
    </style>
</head>
<body>
<div class="dashboard-content">
    <div class="container">
    <h2 class="text-center mb-4">Update Multiple Parcel Status</h2>
        <form method="POST">
            <!-- Static Select Status Option -->
            <div class="sticky-options">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">UPDATE STATUS</label>
                        <select name="status" class="form-select" required>
                            <option value="Shipped">Shipped</option>
                            <option value="In-Transit">In-Transit</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Unsuccessful Delivery Attempt">Unsuccessful Delivery Attempt</option>
                        </select>
                    </div>
                    <br>
                    <div class="col-md-6 text-end mt-3">
                        <button type="submit" class="btn btn-success px-4">Update Selected Parcels</button>
                        <a href="parsel_report.php" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </div>
            </div>

            <!-- Parcel Selection Table -->
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h5>Select Parcels to Update</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Tracking Number</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Total Amount</th>
                                <th>Current Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($parcel = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><input type="checkbox" name="parcel_ids[]" value="<?= $parcel['id']; ?>"></td>
                                    <td><?= $parcel['tracking_number']; ?></td>
                                    <td><?= $parcel['sender_name']; ?></td>
                                    <td><?= $parcel['receiver_name']; ?></td>
                                    <td>₹<?= $parcel['total_amount']; ?></td>
                                    <td><?= $parcel['status']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("selectAll").addEventListener("click", function() {
        let checkboxes = document.querySelectorAll('input[name="parcel_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
</body>
</html>
