<?php
include 'db.php';

$sql = "SELECT tracking_number, sender_name, receiver_name, status FROM parcels";
$result = $conn->query($sql);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
?>
