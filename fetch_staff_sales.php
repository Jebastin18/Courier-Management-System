<?php
include 'db.php';

if (isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];

    // Get Staff Info
    $staffQuery = "SELECT u.name as staff_name, b.branch_name FROM users u
                   JOIN branches b ON u.branch_id = b.id
                   WHERE u.id = ?";
    $stmt = $conn->prepare($staffQuery);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $staffResult = $stmt->get_result()->fetch_assoc();

    // Get Sales Data
    $salesQuery = "SELECT p.tracking_number, p.sender_name AS sender, 
                          p.receiver_name AS receiver, p.total_amount AS price, p.created_at AS date_created
                   FROM parcels p
                   WHERE p.created_by = ?";
    $stmt = $conn->prepare($salesQuery);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $salesResult = $stmt->get_result();

    $sales = [];
    $totalSales = 0;
    while ($row = $salesResult->fetch_assoc()) {
        $sales[] = $row;
        $totalSales += $row['price'];
    }

    echo json_encode([
        "staff_name" => $staffResult['staff_name'],
        "branch_name" => $staffResult['branch_name'],
        "total_sales" => $totalSales,
        "sales" => $sales
    ]);
}
?>
