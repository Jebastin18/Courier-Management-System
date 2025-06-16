<?php
include 'db.php';
if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Parcel ID is missing."]);
    exit;
}
$parcel_id = intval($_GET['id']); 
$query = "SELECT * FROM parcels WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parcel_id);
$stmt->execute();
$result = $stmt->get_result();
$parcel = $result->fetch_assoc();
if (!$parcel) {
    echo json_encode(["status" => "error", "message" => "Parcel not found."]);
    exit;
}
echo json_encode(["status" => "success", "data" => $parcel]);
?>
