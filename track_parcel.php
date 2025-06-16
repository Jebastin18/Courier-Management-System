<?php
include 'db.php';

if (isset($_GET['trackingNumber'])) {
    $trackingNumber = $_GET['trackingNumber'];

    // Fetch parcel details
    $stmt = $conn->prepare("SELECT p.id, p.tracking_number, p.sender_name, p.receiver_name, 
                                   p.status, p.total_amount, 
                                   b_from.branch_name AS from_branch, b_to.branch_name AS to_branch
                            FROM parcels p
                            LEFT JOIN branches b_from ON p.from_branch_id = b_from.id
                            LEFT JOIN branches b_to ON p.to_branch_id = b_to.id
                            WHERE p.tracking_number = ?");
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $parcel = $result->fetch_assoc();
        $parcelId = $parcel['id'];

        // Fetch tracking history
        $stmtHistory = $conn->prepare("SELECT status, updated_at FROM tracking_history WHERE parcel_id = ? ORDER BY updated_at ASC");
        if (!$stmtHistory) {
            die("SQL Error (Tracking History): " . $conn->error);
        }

        $stmtHistory->bind_param("i", $parcelId);
        $stmtHistory->execute();
        $historyResult = $stmtHistory->get_result();
        $history = [];
        while ($row = $historyResult->fetch_assoc()) {
            $history[] = [
                'status' => $row['status'],
                'updated_at' => $row['updated_at']
            ];
        }

        // Fetch item names
        $stmtItems = $conn->prepare("SELECT item_name FROM parcel_items WHERE parcel_id = ?");
        if (!$stmtItems) {
            die("SQL Error (Parcel Items): " . $conn->error);
        }

        $stmtItems->bind_param("i", $parcelId);
        $stmtItems->execute();
        $itemsResult = $stmtItems->get_result();
        $items = [];
        while ($row = $itemsResult->fetch_assoc()) {
            $items[] = $row['item_name'];
        }

    } else {
        $errorMessage = "Tracking number not found.";
    }
} else {
    $errorMessage = "No tracking number provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Parcel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Parcel Tracking Status</h3>
    
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php else: ?>
        <div class="alert alert-info">
            <strong>Tracking Number:</strong> <?php echo $parcel['tracking_number']; ?><br>
            <strong>Sender:</strong> <?php echo $parcel['sender_name']; ?><br>
            <strong>Receiver:</strong> <?php echo $parcel['receiver_name']; ?><br>
            <strong>From Branch:</strong> <?php echo $parcel['from_branch']; ?><br>
            <strong>To Branch:</strong> <?php echo $parcel['to_branch']; ?><br>
            <strong>Current Status:</strong> <span class="badge bg-primary"><?php echo $parcel['status']; ?></span><br>
            <strong>Total Amount:</strong> <?php echo number_format($parcel['total_amount'], 2); ?><br>
        </div>

        <!-- Display Item Names -->
        <h5>Parcel Items:</h5>
        <ul class="list-group">
            <?php foreach ($items as $item): ?>
                <li class="list-group-item"><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>

        <h5 class="mt-3">Status History:</h5>
        <ul class="list-group">
            <?php foreach ($history as $item): ?>
                <li class="list-group-item">
                    <strong><?php echo $item['status']; ?></strong> - <small><?php echo $item['updated_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Download Invoice Button -->
        <a href="generate_invoice.php?trackingNumber=<?php echo $parcel['tracking_number']; ?>" class="btn btn-success mt-3">
            Download Invoice
        </a>
    <?php endif; ?>
</div>
</body>
</html>
