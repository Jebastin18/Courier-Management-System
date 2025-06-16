<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_POST['trackingNumber'])) {
    $trackingNumber = $_POST['trackingNumber'];

    // Step 1: Get Parcel details including from_branch and to_branch
    $stmt = $conn->prepare("SELECT p.id, p.tracking_number, p.sender_name, p.receiver_name, p.status, 
                                   b_from.branch_name AS from_branch, b_to.branch_name AS to_branch
                            FROM parcels p
                            LEFT JOIN branches b_from ON p.from_branch_id = b_from.id
                            LEFT JOIN branches b_to ON p.to_branch_id = b_to.id
                            WHERE p.tracking_number = ?");
    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die(json_encode(["success" => false, "message" => "Query failed: " . $conn->error]));
    }

    if ($result->num_rows > 0) {
        $parcel = $result->fetch_assoc();
        $parcelId = $parcel['id']; // Get parcel_id from the result

        // Step 2: Fetch status history using parcel_id
        $stmtHistory = $conn->prepare("SELECT status, updated_at FROM tracking_history WHERE parcel_id = ? ORDER BY updated_at ASC");
        if (!$stmtHistory) {
            die(json_encode(["success" => false, "message" => "Prepare failed (History): " . $conn->error]));
        }
        
        $stmtHistory->bind_param("i", $parcelId);
        $stmtHistory->execute();
        $historyResult = $stmtHistory->get_result();

        if (!$historyResult) {
            die(json_encode(["success" => false, "message" => "Query failed (History): " . $conn->error]));
        }

        $history = [];
        while ($row = $historyResult->fetch_assoc()) {
            $history[] = [
                'status' => $row['status'],
                'updated_at' => $row['updated_at']
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'tracking_number' => $parcel['tracking_number'],
                'sender_name' => $parcel['sender_name'],
                'receiver_name' => $parcel['receiver_name'],
                'from_branch' => $parcel['from_branch'],
                'to_branch' => $parcel['to_branch'],
                'current_status' => $parcel['status'],
                'history' => $history
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tracking number not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No tracking number provided.']);
}
?>
