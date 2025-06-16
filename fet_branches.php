<?php
include 'db.php';
$query = "SELECT id, branch_name FROM branches"; 
$result = mysqli_query($conn, $query);
$branches = [];
while ($row = mysqli_fetch_assoc($result)) {
    $branches[] = ['id' => $row['id'], 'name' => $row['branch_name']];
}
echo json_encode($branches);
?>
