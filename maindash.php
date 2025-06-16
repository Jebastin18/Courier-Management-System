<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'topbar.php'; ?>
<?php include 'sidebar.php'; ?>

<script>
function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    let content = document.getElementById("main-content");

    if (window.innerWidth > 992) { 
        sidebar.classList.toggle("closed");
        content.classList.toggle("full-width");
    } else { 
        sidebar.classList.toggle("open");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Dropdown Toggle Functionality
    let dropdownButtons = document.querySelectorAll(".dropdown-btn");

    dropdownButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let parentDropdown = this.parentElement;
            
            // Close all other dropdowns before opening the selected one
            document.querySelectorAll(".dropdown").forEach(dropdown => {
                if (dropdown !== parentDropdown) {
                    dropdown.classList.remove("active");
                    dropdown.querySelector(".dropdown-content").style.display = "none";
                }
            });

            // Toggle the current dropdown
            parentDropdown.classList.toggle("active");
            let dropdownContent = parentDropdown.querySelector(".dropdown-content");
            if (dropdownContent) {
                dropdownContent.style.display = parentDropdown.classList.contains("active") ? "block" : "none";
            }
        });
    });

    // Highlight Active Menu Item
    let currentUrl = window.location.pathname.split("/").pop(); // Get the current file name
    let activeLink = document.querySelector('.sidebar a[href="' + currentUrl + '"]');

    if (activeLink) {
        activeLink.classList.add("active");

        // Expand dropdown if the active link is inside one
        let dropdownContent = activeLink.closest(".dropdown-content");
        if (dropdownContent) {
            dropdownContent.style.display = "block";
            dropdownContent.parentElement.classList.add("active"); // Mark the dropdown as active
        }
    }
});
</script>


</body>
</html>
