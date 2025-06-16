<?php
include 'db.php';
$query = "SELECT id, name FROM users WHERE role = 'staff'";
$result = $conn->query($query);
$staff = [];
while ($row = $result->fetch_assoc()) {
    $staff[] = $row;
}
echo json_encode($staff);
?>
