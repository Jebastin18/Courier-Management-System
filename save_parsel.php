<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header("Content-Type: application/json");
include 'db.php';
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_name = $_POST['sender_name'] ?? '';
    $sender_address = $_POST['sender_address'] ?? '';
    $sender_city = $_POST['sender_city'] ?? '';
    $sender_email = $_POST['sender_email'] ?? '';
    $sender_phone = $_POST['sender_phone'] ?? '';
    $sender_pincode = $_POST['sender_pincode'] ?? '';
    $receiver_name = $_POST['receiver_name'] ?? '';
    $receiver_address = $_POST['receiver_address'] ?? '';
    $receiver_city = $_POST['receiver_city'] ?? '';
    $receiver_email = $_POST['receiver_email'] ?? '';
    $receiver_phone = $_POST['receiver_phone'] ?? '';
    $receiver_pincode = $_POST['receiver_pincode'] ?? '';
    $from_branch_id = $_POST['from_branch'] ?? '';
    $to_branch_id = $_POST['to_branch'] ?? '';
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0.00;
    $created_by = $_SESSION['user_id'] ?? 0;

    if ($created_by == 0) {
        echo json_encode(["status" => "error", "message" => "User not authenticated"]);
        exit;
    }
    if (empty($sender_name) || empty($receiver_name) || empty($from_branch_id) || empty($to_branch_id)) {
        echo json_encode(["status" => "error", "message" => "Required fields are missing."]);
        exit;
    }

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO parcels (sender_name, sender_address, sender_city, sender_email, sender_phone, sender_pincode, 
                receiver_name, receiver_address, receiver_city, receiver_email, receiver_phone, receiver_pincode, 
                from_branch_id, to_branch_id, total_amount, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssdi", 
            $sender_name, $sender_address, $sender_city, $sender_email, $sender_phone, $sender_pincode, 
            $receiver_name, $receiver_address, $receiver_city, $receiver_email, $receiver_phone, $receiver_pincode, 
            $from_branch_id, $to_branch_id, $total_amount, $created_by
        );

        if (!$stmt->execute()) {
            throw new Exception("Parcel insertion failed: " . $stmt->error);
        }

        $parcel_id = $conn->insert_id; 
        $tracking_number = "TRK" . str_pad($parcel_id, 5, "0", STR_PAD_LEFT) . date("mdHi");

        $update_sql = "UPDATE parcels SET tracking_number = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("si", $tracking_number, $parcel_id);
        if (!$stmt_update->execute()) {
            throw new Exception("Failed to update tracking number: " . $stmt_update->error);
        }

        if (!empty($_POST['item_name'])) {
            $item_names = $_POST['item_name'];
            $kilograms = $_POST['kilograms'];
            $prices = $_POST['price'];
            $sql_items = "INSERT INTO parcel_items (parcel_id, item_name, kilograms, price) VALUES (?, ?, ?, ?)";
            $stmt_items = $conn->prepare($sql_items);
            for ($i = 0; $i < count($item_names); $i++) {
                $stmt_items->bind_param("isdd", $parcel_id, $item_names[$i], $kilograms[$i], $prices[$i]);
                if (!$stmt_items->execute()) {
                    throw new Exception("Parcel item insertion failed: " . $stmt_items->error);
                }
            }
        }

        $conn->commit();

        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jebastinr817@gmail.com'; // Replace with your email
            $mail->Password = 'kpwb bhiu yvft qcdy'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jebastinr817@gmail.com', 'Nova Courier');
            $mail->addAddress($sender_email, $sender_name);
            $mail->addAddress($receiver_email, $receiver_name);

            $mail->isHTML(true);
            $mail->Subject = 'Parcel Shipment Confirmation - Tracking Number: ' . $tracking_number;
            $mail->Body = "
                <h2>Parcel Shipment Details</h2>
                <p>Dear $sender_name,</p>
                <p>Your parcel has been successfully registered.</p>
                <p><strong>Tracking Number:</strong> $tracking_number</p>
                <p><strong>From:</strong> $sender_city</p>
                <p><strong>To:</strong> $receiver_city</p>
                <p><strong>Total Amount:</strong> $total_amount</p>
                <p>You can track your parcel using the tracking number above.</p>
                <p>Thank you for choosing Nova Courier.</p>
            ";

            if ($mail->send()) {
                echo json_encode(["status" => "success", "message" => "Parcel and items saved successfully", "tracking_number" => $tracking_number, "email_status" => "Email sent"]);
            } else {
                echo json_encode(["status" => "success", "message" => "Parcel and items saved successfully", "tracking_number" => $tracking_number, "email_status" => "Email not sent"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "success", "message" => "Parcel and items saved successfully", "tracking_number" => $tracking_number, "email_status" => "Email error: " . $mail->ErrorInfo]);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
