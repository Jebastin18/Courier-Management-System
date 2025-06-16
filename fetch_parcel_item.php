<?php
include 'db.php';
$parcel_id = $_GET['id'] ?? 0;
if ($parcel_id) {
    $stmt = $conn->prepare("SELECT * FROM parcel_items WHERE parcel_id = ?");
    $stmt->bind_param("i", $parcel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    echo json_encode([
        'status' => 'success',
        'items' => $items
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid Parcel ID'
    ]);
}
?>