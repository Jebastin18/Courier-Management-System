<?php
// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$role = $_SESSION["role"] ?? ''; 
?>


<!-- Sidebar Menu -->
<div class="sidebar" id="sidebar">
    <a href="home.php" class="<?= ($currentPage == 'home.php') ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Home
    </a>

    <?php if ($role === 'Admin'): ?>
    <!-- Branch Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-btn">
            <i class="fas fa-building"></i> Branch <i class="fas fa-chevron-down"></i>
        </a>
        <div class="dropdown-content">
            <a href="create_branch.php" class="<?= ($currentPage == 'create_branch.php') ? 'active' : '' ?>">
                <i class="fas fa-plus"></i> Create Branch
            </a>
            <a href="branch_edit.php" class="<?= ($currentPage == 'branch_edit.php') ? 'active' : '' ?>">
                <i class="fas fa-edit"></i> Edit Branch
            </a>
            <a href="branch_report.php" class="<?= ($currentPage == 'branch_report.php') ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Branch Report
            </a>
        </div>
    </div>

    <!-- Users Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-btn">
            <i class="fas fa-users"></i> Users <i class="fas fa-chevron-down"></i>
        </a>
        <div class="dropdown-content">
            <a href="user_create.php" class="<?= ($currentPage == 'user_create.php') ? 'active' : '' ?>">
                <i class="fas fa-user-plus"></i> Create User
            </a>
            <a href="user_edit.php" class="<?= ($currentPage == 'user_edit.php') ? 'active' : '' ?>">
                <i class="fas fa-user-edit"></i> Edit User
            </a>
            <a href="user_report.php" class="<?= ($currentPage == 'user_report.php') ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> User Report
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Parcels & Reports -->
    <a href="parsel_create.php" class="<?= ($currentPage == 'parsel_create.php') ? 'active' : '' ?>">
        <i class="fas fa-box"></i> Parcels
    </a>
    <a href="parsel_report.php" class="<?= ($currentPage == 'parsel_report.php') ? 'active' : '' ?>">
        <i class="fas fa-chart-line"></i> Parcel Reports
    </a>
    <a href="update_parcel_status.php" class="<?= ($currentPage == 'update_parcel_status.php') ? 'active' : '' ?>">
    <i class="fas fa-sync-alt"></i> Update Status
    </a>

    <!-- Status & Tracking -->
    <a href="status_report.php" class="<?= ($currentPage == 'status_report.php') ? 'active' : '' ?>">
        <i class="fas fa-truck"></i> Status Report
    </a>
    <a href="track.php" class="<?= ($currentPage == 'track.php') ? 'active' : '' ?>">
        <i class="fas fa-route"></i> Tracking
    </a>
    <a href="send_tracking_link.php" class="<?= ($currentPage == 'send_tracking_link.php') ? 'active' : '' ?>">
        <i class="fas fa-envelope-open"></i> Via Email
    </a>
</div>

<!-- Ensure FontAwesome is Loaded (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
