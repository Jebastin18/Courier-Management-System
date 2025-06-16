<?php 
session_start();
include 'db.php';
include 'maindash.php';
if (!isset($_SESSION["role"]) || !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION["role"];
$user_id = $_SESSION["user_id"];
function fetchData($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result->fetch_assoc()['total'] ?? 0;
}
if ($role === 'Admin') {
    $totalParcelsQuery = "SELECT COUNT(*) AS total FROM parcels";
    $totalBranchesQuery = "SELECT COUNT(*) AS total FROM branches";
    $totalStaffQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'Staff'";
} else if ($role === 'Staff') {
    $totalParcelsQuery = "SELECT COUNT(*) AS total FROM parcels WHERE created_by = '$user_id'";
    $totalBranchesQuery = "SELECT COUNT(*) AS total FROM branches";
    $totalStaffQuery = "SELECT COUNT(*) AS total FROM users WHERE id = '$user_id'";
} else {
    header("Location: login.php");
    exit();
}
$totalParcels = fetchData($conn, $totalParcelsQuery);
$totalBranches = fetchData($conn, $totalBranchesQuery);
$totalStaff = fetchData($conn, $totalStaffQuery);
$statusQuery = $role === 'Staff' ?
    "SELECT status, COUNT(*) as count FROM parcels WHERE created_by = '$user_id' GROUP BY status" :
    "SELECT status, COUNT(*) as count FROM parcels GROUP BY status";
$statusResult = $conn->query($statusQuery);
$statusLabels = [];
$statusData = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusLabels[] = $row['status'];
    $statusData[] = $row['count'];
}
if (empty($statusLabels)) {
    $statusLabels = ["No Data"];
    $statusData = [1];
}
$monthQuery = "SELECT DATE_FORMAT(created_at, '%M %Y') as month, COUNT(*) as count 
               FROM parcels 
               GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
               ORDER BY MIN(created_at)";
$monthResult = $conn->query($monthQuery);
$monthLabels = [];
$monthData = [];
while ($row = $monthResult->fetch_assoc()) {
    $monthLabels[] = $row['month'];
    $monthData[] = $row['count'];
}
if (empty($monthLabels)) {
    $monthLabels = ["No Data"];
    $monthData = [0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nova Courier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-content {
            margin-left: 250px;
            padding: 50px;
            transition: all 0.3s ease-in-out;
        }
        @media (max-width: 992px) {
            .dashboard-content {
                margin-left: 0;
                padding: 15px;
            }
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        p.text-center {
            margin-top: 20px;
            padding: 10px;
            font-size: 1.1rem;
        }  
    </style>
</head>
<body>
<div class="dashboard-content">
    <div class="container">
        <p class="text-center text-muted mt-4 p-3 bg-light rounded shadow-sm">
            Welcome, <strong class="text-uppercase"><?php echo ucfirst($role); ?></strong>. Manage your courier system efficiently.
        </p>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card text-white bg-primary shadow-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Parcels</h5>
                        <h2 class="fw-bold"><?php echo $totalParcels; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-success shadow-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Branches</h5>
                        <h2 class="fw-bold"><?php echo $totalBranches; ?></h2>
                    </div>
                </div>
            </div>
            <?php if ($role === 'Admin'): ?>
            <div class="col">
                <div class="card text-white bg-warning shadow-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Staff</h5>
                        <h2 class="fw-bold"><?php echo $totalStaff; ?></h2>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="row mt-5">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-info text-white text-center fw-bold">Parcel Status Distribution</div>
                    <div class="card-body">
                        <canvas id="parcelPieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center fw-bold">Monthly Shipments</div>
                    <div class="card-body">
                        <canvas id="shipmentBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($role === 'Admin'): ?> 
<div class="row justify-content-center mt-4">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center">
                <h3 class="mb-3 text-primary"><i class="fas fa-chart-line"></i> Reports</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="sales.php" class="text-decoration-none fw-bold text-dark">
                            <i class="fas fa-file-invoice-dollar text-success"></i> Sales Report
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="staff_sales_report.php" class="text-decoration-none fw-bold text-dark">
                            <i class="fas fa-user-tie text-info"></i> Staff Report
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx1 = document.getElementById('parcelPieChart').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($statusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($statusData); ?>,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8', '#6610f2', '#fd7e14', '#6f42c1', '#20c997']
            }]
        }
    });
    var ctx2 = document.getElementById('shipmentBarChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthLabels); ?>,
            datasets: [{
                label: 'Shipments',
                data: <?php echo json_encode($monthData); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
</body>
</html>
