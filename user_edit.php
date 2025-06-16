<?php 
include 'db.php'; 
include 'maindash.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
        <h2 class="text-center mb-4">Update Staff</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Update User Details</div>
            <div class="card-body">
                <form id="updateUserForm">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        <select id="selectUser" class="form-select select2" name="user_id" required>
                            <option value="" selected disabled>Select a user</option>
                        </select>
                    </div>
                    <input type="hidden" name="user_id" id="user_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
    <label class="form-label">New Password (leave empty to keep current)</label>
    <input type="password" id="password" name="password" class="form-control">
</div>

                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhar Number</label>
                            <input type="text" id="aadhar_number" name="aadhar_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,12);">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control" maxlength="50" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-center">
                        <button type="submit" class="btn btn-success w-50">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="message" class="mt-3 text-center"></div>
    </div>
</div>

<script>
$(document).ready(function(){
    $(".select2").select2();

    // Fetch users for Select2 dropdown
    $.ajax({
        url: "fetch_users.php",
        type: "GET",
        dataType: "json",
        success: function(data){
            let options = '<option value="" disabled selected>Select a user</option>';
            $.each(data, function(index, user){
                options += `<option value="${user.id}">${user.username}</option>`;
            });
            $("#selectUser").html(options);
        }
    });

    // Fetch user details when a username is selected
    $("#selectUser").change(function(){
        let userId = $(this).val();
        if(userId){
            $.ajax({
                url: "fetch_update.php",
                type: "GET",
                data: { user_id: userId },
                dataType: "json",
                success: function(data){
                    $("#user_id").val(data.id);
                    $("#name").val(data.name);
                    $("#username").val(data.username);
                    $("#email").val(data.email);
                    $("#address").val(data.address);
                    $("#aadhar_number").val(data.aadhar_number);
                    $("#phone_number").val(data.phone_number);
                    $("#role").val(data.role);
                }
            });
        }
    });

    // Update user details on form submission
    $("#updateUserForm").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "update-users.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response){
                alert(response);
                $("#updateUserForm")[0].reset();
                $(".select2").val(null).trigger("change");
            }
        });
    });
});
</script>
</body>
</html>
