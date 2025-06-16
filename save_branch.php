<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_code = $_POST['branch_code'];
    $branch_name = $_POST['branch_name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // Check if the branch code already exists
    $check = $conn->prepare("SELECT * FROM branches WHERE branch_code = ?");
    $check->bind_param("s", $branch_code);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "exists"; // Branch code already exists
    } else {
        // Insert new branch data
        $stmt = $conn->prepare("INSERT INTO branches (branch_code, branch_name, city, state, phone_number, address, pincode) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssss", $branch_code, $branch_name, $city, $state, $phone_number, $address, $pincode);
            if ($stmt->execute()) {
                echo "success"; // Branch registered successfully
            } else {
                echo "SQL Error: " . $stmt->error; // Error in execution
            }
            $stmt->close();
        } else {
            echo "SQL Prepare Error: " . $conn->error; // Error in preparing the statement
        }
    }

    $check->close();
    $conn->close();
}
?>
