<?php 
include 'db.php'; 
include 'maindash.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .dashboard-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease-in-out;
        }
        .btn-center {
            display: flex;
            justify-content: center;
        }
        @media (max-width: 992px) {
            .dashboard-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-content">
    <div class="container">
        <h2 class="text-center mb-4">Branch</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Create New Branch</div>
            <div class="card-body">
                <form id="branchForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Code</label>
                            <input type="text" name="branch_code" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="branch_name" class="form-control" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                            <small class="text-danger d-none" id="phoneError">Phone number must be exactly 10 digits.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" maxlength="100" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                            <small class="text-danger d-none" id="pincodeError">Pincode must be exactly 6 digits.</small>
                        </div>
                    </div>
                    <div class="btn-center">
                        <button type="submit" class="btn btn-success w-50">Save Branch</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="message" class="mt-3 text-center"></div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#branchForm").on("submit", function(e){
        e.preventDefault();
        let valid = true;
        let phone = $("input[name='phone_number']").val();
        let pincode = $("input[name='pincode']").val();      

        if (!/^\d{10}$/.test(phone)) {
            $("#phoneError").removeClass("d-none");
            valid = false;
        } else {
            $("#phoneError").addClass("d-none");
        }

        if (!/^\d{6}$/.test(pincode)) {
            $("#pincodeError").removeClass("d-none");
            valid = false;
        } else {
            $("#pincodeError").addClass("d-none");
        }

        if (valid) {
            $.ajax({
                url: "save_branch.php",
                type: "POST",
                data: $("#branchForm").serialize(),
                success: function(response){
                    response = response.trim();
                    if(response === "success") {
                        alert("Branch Registered Successfully!");
                        $("#branchForm")[0].reset();
                    } else if(response === "exists") {
                        alert("Branch Code already exists!");
                    } else {
                        alert("Error: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Something went wrong! Try again.");
                }
            });
        }
    });
});
</script>
</body>
</html>
