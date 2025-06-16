<?php
include 'db.php';
include 'maindash.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Parcel - Nova Courier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .tracking-container {
            max-width: 550px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 15%;
        }
        .tracking-status {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .timeline {
            list-style: none;
            padding: 0;
            margin-top: 10px;
            position: relative;
        }
        .timeline::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 0;
            width: 3px;
            height: 100%;
            background: #007bff;
        }
        .timeline li {
            position: relative;
            padding: 10px 0 10px 40px;
        }
        .timeline li::before {
            content: "●";
            position: absolute;
            left: 5px;
            top: 10px;
            font-size: 14px;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="tracking-container">
        <h3 class="text-center"><i class="fas fa-search"></i> Track Your Parcel</h3>
        <form id="trackForm">
            <div class="mb-3">
                <label for="trackingNumber" class="form-label">Enter Tracking Number:</label>
                <input type="text" id="trackingNumber" name="trackingNumber" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Track</button>
        </form>
        <div id="trackingResult" class="tracking-status"></div>
    </div>
</div>

<!-- Include jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $("#trackForm").submit(function(e) {
        e.preventDefault();
        var trackingNumber = $("#trackingNumber").val();

        $.ajax({
            url: "fetch_tracking.php",
            type: "POST",
            data: { trackingNumber: trackingNumber },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let historyHtml = `<strong>Tracking Number:</strong> ${response.data.tracking_number}<br>
                        <strong>Sender:</strong> ${response.data.sender_name}<br>
                        <strong>Receiver:</strong> ${response.data.receiver_name}<br>
                        <strong>From Branch:</strong> ${response.data.from_branch}<br>
                        <strong>To Branch:</strong> ${response.data.to_branch}<br>
                        <strong>Current Status:</strong> <span class="badge bg-info">${response.data.current_status}</span><br>
                        <h5 class="mt-3">Status History:</h5><ul class="timeline">`;

                    response.data.history.forEach(item => {
                        historyHtml += `<li><strong>${item.status}</strong> - <small>${item.updated_at}</small></li>`;
                    });

                    historyHtml += `</ul>`;

                    $("#trackingResult").html(historyHtml)
                        .removeClass("alert-danger")
                        .addClass("alert-success")
                        .fadeIn();
                } else {
                    $("#trackingResult").html(response.message)
                        .removeClass("alert-success")
                        .addClass("alert-danger")
                        .fadeIn();
                }
            },
            error: function() {
                $("#trackingResult").html("Error retrieving tracking details. Please try again.")
                    .addClass("alert-danger")
                    .fadeIn();
            }
        });
    });
});
</script>
</body>
</html>
