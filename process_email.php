<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trackingNumber = $_POST['trackingNumber'];
    $customerEmail = $_POST['email'];

    // Validate Tracking Number
    $query = $conn->prepare("SELECT * FROM parcels WHERE tracking_number = ?");
    $query->bind_param("s", $trackingNumber);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $trackingLink = "http://192.168.1.3/courier/track_parcel.php?trackingNumber="  . urlencode($trackingNumber);

        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'jebastinr817@gmail.com'; // Your Gmail
            $mail->Password = 'kpwb bhiu yvft qcdy'; // Your App Password (Not Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Content
            $mail->setFrom('jebastinr817@gmail.com', 'Nova Courier');
            $mail->addAddress($customerEmail);
            $mail->isHTML(true);
            $mail->Subject = "Your Parcel Tracking Link -Courier";
            $mail->Body = "Hello,<br><br>Track your parcel using the link below:<br>
                          <a href='$trackingLink'>$trackingLink</a><br><br>Thank you for choosing Nova Courier!";

            $mail->send();
            echo "<div class='alert alert-success'>Tracking link sent to <strong>$customerEmail</strong> successfully.</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Mailer Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid Tracking Number. Please check and try again.</div>";
    }
}
?>
