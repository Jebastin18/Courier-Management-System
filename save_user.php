<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $aadhar_number = $_POST['aadhar_number'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $branch_id = $_POST['branch']; // Get the branch ID

    // Fetch branch name from the branches table
    $branch_stmt = $conn->prepare("SELECT branch_name FROM branches WHERE id = ?");
    $branch_stmt->bind_param("i", $branch_id);
    $branch_stmt->execute();
    $branch_result = $branch_stmt->get_result();

    if ($branch_result->num_rows > 0) {
        $branch_row = $branch_result->fetch_assoc();
        $branch_name = $branch_row['branch_name'];
    } else {
        echo "Invalid Branch ID"; // Handle error if branch_id doesn't exist
        exit();
    }
    $branch_stmt->close();

    // Check if username or email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "exists"; 
    } else {
        // Insert the new user with branch_id and branch_name
        $stmt = $conn->prepare("INSERT INTO users (name, username, email, address, password, aadhar_number, phone_number, role, branch_id, branch_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssssssis", $name, $username, $email, $address, $password, $aadhar_number, $phone_number, $role, $branch_id, $branch_name);
            if ($stmt->execute()) {
                echo "success"; 
            } else {
                echo "SQL Error: " . $stmt->error; 
            }
            $stmt->close();
        } else {
            echo "SQL Prepare Error: " . $conn->error; 
        }
    }

    $check->close();
    $conn->close();
}
?>
