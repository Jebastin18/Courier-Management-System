<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $selected_role = trim($_POST["role"]); 
    $stmt = $conn->prepare("SELECT id, username, password, role, status FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $hashed_password, $db_role, $status);
        $stmt->fetch();
        if ($status !== "active") {
            echo "Your account is inactive. Please contact admin.";
        } 
        elseif ($selected_role !== $db_role) {
            echo "Invalid role selected!";
        } 
        elseif (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $db_username;
            $_SESSION["role"] = $db_role;
            echo "success";
        } else {
            echo "Invalid Password";
        }
    } else {
        echo "Username not found";
    }
    $stmt->close();
    $conn->close();
}
?>
