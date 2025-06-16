<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = $_POST['branch_id'];
    $branch_code = $_POST['branch_code'];
    $branch_name = $_POST['branch_name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    $stmt = $conn->prepare("UPDATE branches SET branch_code=?, branch_name=?, city=?, state=?, phone_number=?, address=?, pincode=? WHERE id=?");
    $stmt->bind_param("sssssssi", $branch_code, $branch_name, $city, $state, $phone_number, $address, $pincode, $branch_id);
    
    if ($stmt->execute()) {
        echo "Branch updated successfully";
    } else {
        echo "SQL Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
