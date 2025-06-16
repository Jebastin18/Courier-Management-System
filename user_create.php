<?php 
include 'db.php'; 
include 'maindash.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
        <h2 class="text-center mb-4">Staff Registration</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Register New Staff</div>
            <div class="card-body">
                <form id="userForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                            <small class="text-danger d-none" id="phoneError">Phone number must be exactly 10 digits.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                            <small class="text-danger d-none" id="passwordError">Password must be at least 6 characters.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhar Number</label>
                            <input type="text" name="aadhar_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,12);">
                            <small class="text-danger d-none" id="aadharError">Aadhar must be exactly 12 digits.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch</label>
                            <select name="branch" id="branchSelect" class="form-select" required>
                                <option value="" disabled selected>Select Branch</option>
                            </select>
                            <small class="text-danger d-none" id="branchError">Please select a branch.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                            <small class="text-danger d-none" id="roleError">Please select a role</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" maxlength="50" required></textarea>
                        </div>
                    </div>
                    <div class="btn-center">
                        <button type="submit" class="btn btn-success w-50">Register</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="message" class="mt-3 text-center"></div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Initialize Select2
    $('#branchSelect').select2({
        placeholder: "Select Branch",
        allowClear: true
    });

    // Fetch branch data
    function loadBranches() {
        $.ajax({
            url: 'fet_branches.php', // PHP file to fetch branches
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#branchSelect').empty().append('<option value="" disabled selected>Select Branch</option>');
                $.each(data, function(index, branch) {
                    $('#branchSelect').append('<option value="' + branch.id + '">' + branch.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching branches:", error);
            }
        });
    }

    loadBranches();

    $("#userForm").on("submit", function(e){
        e.preventDefault();
        let valid = true;
        let aadhar = $("input[name='aadhar_number']").val();
        let phone = $("input[name='phone_number']").val();
        let password = $("input[name='password']").val();
        let role = $("select[name='role']").val();
        let branch = $("select[name='branch']").val();

        if (!/^\d{12}$/.test(aadhar)) {
            $("#aadharError").removeClass("d-none");
            valid = false;
        } else {
            $("#aadharError").addClass("d-none");
        }

        if (!/^\d{10}$/.test(phone)) {
            $("#phoneError").removeClass("d-none");
            valid = false;
        } else {
            $("#phoneError").addClass("d-none");
        }

        if (password.length < 6) {
            $("#passwordError").removeClass("d-none");
            valid = false;
        } else {
            $("#passwordError").addClass("d-none");
        }

        if (!role) {
            $("#roleError").removeClass("d-none");
            valid = false;
        } else {
            $("#roleError").addClass("d-none");
        }

        if (!branch) {
            $("#branchError").removeClass("d-none");
            valid = false;
        } else {
            $("#branchError").addClass("d-none");
        }

        if (valid) {
            $.ajax({
                url: "save_user.php",
                type: "POST",
                data: $("#userForm").serialize(),
                success: function(response){
                    response = response.trim();
                    if(response === "success") {
                        alert("Registration Successful!");
                        $("#userForm")[0].reset();
                    } else if(response === "exists") {
                        alert("Username or Email already exists!");
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
