<?php
include 'db.php';
$query = "SELECT id, branch_code FROM branches";
$result = $conn->query($query);
$branches = [];
while ($row = $result->fetch_assoc()) {
    $branches[] = $row;
}
echo json_encode($branches);
?>
