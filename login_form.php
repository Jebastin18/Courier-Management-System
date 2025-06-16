<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .login-container {
            height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center login-container">
    <div class="card login-card">
        <div class="card-header bg-primary text-white text-center">
            <h4>User Login</h4>
        </div>
        <div class="card-body">
            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                    <option value="">Select Role</option>    
                    <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
            <div id="message" class="mt-3 text-center"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#loginForm").on("submit", function(e){
        e.preventDefault();    
        $.ajax({
            url: "login.php",
            type: "POST",
            data: $("#loginForm").serialize(),
            success: function(response){
                if(response.trim() === "success") {
                    window.location.href = "home.php"; 
                } else {
                    $("#message").html('<div class="alert alert-danger">' + response + '</div>');
                }
            }
        });
    });
    $("#togglePassword").click(function(){
        let passwordField = $("#password");
        let type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash"); 
    });
});
</script>
</body>
</html>
