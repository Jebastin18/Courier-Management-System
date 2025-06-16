<?php
include 'db.php';
$branch_id = $_POST['branch_id'] ?? 0;
$sql = "SELECT p.reference_no, p.sender_name, p.recipient_name, 
               p.weight, p.total_amount, p.created_at
        FROM parcels p
        JOIN users u ON p.created_by = u.id
        WHERE u.branch_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$sales = [];
$total_sales = 0;
while ($row = $result->fetch_assoc()) {
    $sales[] = [
        $row['reference_no'],
        $row['sender_name'],
        $row['recipient_name'],
        $row['weight'],
        "₹" . number_format($row['total_amount'], 2), 
        date("d-m-Y H:i", strtotime($row['created_at'])) 
    ];
    $total_sales += $row['total_amount'];
}
echo json_encode([
    "data" => $sales,
    "total_sales" => "₹" . number_format($total_sales, 2)
]);
?>
