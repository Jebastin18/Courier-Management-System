<?php
include 'db.php';
include 'maindash.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Tracking Link - Nova Courier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .email-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 15%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="email-container">
        <h3 class="text-center"><i class="fas fa-envelope"></i> Get Tracking Link via Email</h3>
        <form id="emailForm">
            <div class="mb-3">
                <label for="trackingNumber" class="form-label">Enter Tracking Number:</label>
                <input type="text" id="trackingNumber" name="trackingNumber" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Enter Your Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane"></i> Send Link</button>
        </form>
        <div id="emailResponse" class="mt-3"></div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#emailForm").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "process_email.php",
            type: "POST",
            data: formData,
            success: function(response) {
                $("#emailResponse").html(response).fadeIn();
            },
            error: function() {
                $("#emailResponse").html("<div class='alert alert-danger'>Error sending email. Try again.</div>").fadeIn();
            }
        });
    });
});
</script>
</body>
</html>
