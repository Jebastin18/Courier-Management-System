<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['parcel_ids'])) {
    $parcel_ids = $_POST['parcel_ids']; // Array of selected parcel IDs
    $new_status = $_POST['status']; // New status selected

    if (!empty($parcel_ids)) {
        // Convert IDs array into a comma-separated string for SQL query
        $parcel_ids_str = implode(',', array_map('intval', $parcel_ids));

        // Update status for selected parcels
        $update_sql = "UPDATE parcels SET status = '$new_status' WHERE id IN ($parcel_ids_str)";
        
        if ($conn->query($update_sql) === TRUE) {
            // Insert status update history
            foreach ($parcel_ids as $parcel_id) {
                $history_sql = "INSERT INTO tracking_history (parcel_id, status) VALUES ('$parcel_id', '$new_status')";
                $conn->query($history_sql);
            }

            echo "<script>alert('Selected parcel statuses updated successfully and history recorded!'); window.location.href='parsel_report.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating parcel statuses');</script>";
        }
    }
}

?>