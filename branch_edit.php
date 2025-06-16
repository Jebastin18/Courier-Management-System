<?php 
include 'db.php'; 
include 'maindash.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Branch</title>
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
        <h2 class="text-center mb-4">Update Branch</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Update Branch Details</div>
            <div class="card-body">
                <form id="updateBranchForm">
                    <div class="mb-3">
                        <label class="form-label">Select Branch</label>
                        <select id="selectBranch" class="form-select select2" name="branch_id" required>
                            <option value="" selected disabled>Select a branch</option>
                        </select>
                    </div>
                    <input type="hidden" name="branch_id" id="branch_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Code</label>
                            <input type="text" id="branch_code" name="branch_code" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Branch Name</label>
                            <input type="text" id="branch_name" name="branch_name" class="form-control" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" id="city" name="city" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" id="state" name="state" class="form-control" maxlength="25" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" id="pincode" name="pincode" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control" maxlength="100" required></textarea>
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
    $.ajax({
        url: "fetch_branches.php",
        type: "GET",
        dataType: "json",
        success: function(data){
            let options = '<option value="" disabled selected>Select a branch</option>';
            $.each(data, function(index, branch){
                options += `<option value="${branch.id}">${branch.branch_code}</option>`;
            });
            $("#selectBranch").html(options);
        }
    });
    $("#selectBranch").change(function(){
        let branchId = $(this).val();
        if(branchId){
            $.ajax({
                url: "fetch_branch_update.php",
                type: "GET",
                data: { branch_id: branchId },
                dataType: "json",
                success: function(data){
                    $("#branch_id").val(data.id);
                    $("#branch_code").val(data.branch_code);
                    $("#branch_name").val(data.branch_name);
                    $("#city").val(data.city);
                    $("#state").val(data.state);
                    $("#phone_number").val(data.phone_number);
                    $("#pincode").val(data.pincode);
                    $("#address").val(data.address);
                }
            });
        }
    });
    $("#updateBranchForm").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "update_branch.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response){
                alert(response);
                $("#updateBranchForm")[0].reset();
                $(".select2").val(null).trigger("change");
            }
        });
    });
});
</script>
</body>
</html>
