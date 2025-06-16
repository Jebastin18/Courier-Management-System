<?php
include 'db.php';
if(isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
    $stmt = $conn->prepare("SELECT * FROM branches WHERE id = ?");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $branch = $result->fetch_assoc();
    echo json_encode($branch);
    $stmt->close();
    $conn->close();
}
?>
