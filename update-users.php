<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $aadhar_number = $_POST['aadhar_number'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, address=?, aadhar_number=?, phone_number=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $name, $username, $email, $address, $aadhar_number, $phone_number, $role, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, address=?, aadhar_number=?, phone_number=?, role=? WHERE id=?");
        $stmt->bind_param("sssssssi", $name, $username, $email, $address, $aadhar_number, $phone_number, $role, $user_id);
    }

    if ($stmt->execute()) {
        echo "User updated successfully";
    } else {
        echo "Error updating user";
    }
}
?>
