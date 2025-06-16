<?php
include 'db.php';

header('Content-Type: application/json');

if (!isset($_POST['branch_id'])) {
    echo json_encode(["error" => "Branch ID not provided"]);
    exit();
}

$branch_id = intval($_POST['branch_id']);

$query = "SELECT 
                b.branch_name,
                COUNT(p.id) AS total_parcels,
                COALESCE(SUM(p.total_amount), 0) AS total_revenue,
                SUM(CASE WHEN p.status = 'Delivered' THEN 1 ELSE 0 END) AS delivered_count,
                SUM(CASE WHEN p.status = 'In-Transit' THEN 1 ELSE 0 END) AS in_transit_count,
                SUM(CASE WHEN p.status = 'Pending' THEN 1 ELSE 0 END) AS pending_count
              FROM parcels p
              JOIN branches b ON p.from_branch_id = b.id
              WHERE b.id = ?
              GROUP BY b.id";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo json_encode(["error" => "No data found for selected branch"]);
} else {
    echo json_encode($data);
}

$stmt->close();
$conn->close();
?>
