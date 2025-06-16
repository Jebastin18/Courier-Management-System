<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parcel_id = $_POST['parcel_id'];

    // Validate parcel_id
    if (empty($parcel_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid Parcel ID"]);
        exit;
    }

    // Update main parcel details
    $sender_name = $_POST['sender_name'];
    $sender_address = $_POST['sender_address'];
    $sender_city = $_POST['sender_city'];
    $sender_email = $_POST['sender_email'];
    $sender_phone = $_POST['sender_phone'];
    $sender_pincode = $_POST['sender_pincode'];
    $receiver_name = $_POST['receiver_name'];
    $receiver_address = $_POST['receiver_address'];
    $receiver_city = $_POST['receiver_city'];
    $receiver_email = $_POST['receiver_email'];
    $receiver_phone = $_POST['receiver_phone'];
    $receiver_pincode = $_POST['receiver_pincode'];

    // Update parcel details without from_branch and to_branch
    $updateQuery = "UPDATE parcels SET 
        sender_name=?, sender_address=?, sender_city=?, sender_email=?, sender_phone=?, sender_pincode=?,
        receiver_name=?, receiver_address=?, receiver_city=?, receiver_email=?, receiver_phone=?, receiver_pincode=?
        WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("ssssssssssssi", 
        $sender_name, $sender_address, $sender_city, $sender_email, $sender_phone, $sender_pincode,
        $receiver_name, $receiver_address, $receiver_city, $receiver_email, $receiver_phone, $receiver_pincode,
        $parcel_id);
    
    if ($stmt->execute()) {
        // Delete old parcel items
        $deleteQuery = "DELETE FROM parcel_items WHERE parcel_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("i", $parcel_id);
        $stmt->execute();
        $stmt->close();

        // Insert new parcel items
        if (isset($_POST['item_name'])) {
            $insertItemQuery = "INSERT INTO parcel_items (parcel_id, item_name, kilograms, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertItemQuery);
            if (!$stmt) {
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            for ($i = 0; $i < count($_POST['item_name']); $i++) {
                $item_name = $_POST['item_name'][$i];
                $kilograms = $_POST['kilograms'][$i];
                $price = $_POST['price'][$i];
                $stmt->bind_param("isdd", $parcel_id, $item_name, $kilograms, $price);
                $stmt->execute();
            }
            $stmt->close();
        }

        echo json_encode(["status" => "success", "message" => "Parcel updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating parcel: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>