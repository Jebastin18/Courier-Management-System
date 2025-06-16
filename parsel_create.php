<?php
session_start();
ob_start();
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    try {
        // Validate required fields
        $required = [
            'sender_name', 'sender_address', 'sender_city', 'sender_email', 'sender_phone', 'sender_pincode',
            'receiver_name', 'receiver_address', 'receiver_city', 'receiver_email', 'receiver_phone', 'receiver_pincode',
            'from_branch', 'to_branch', 'total_amount'
        ];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Start transaction
        $conn->begin_transaction();

        // Insert parcel
        $stmt = $conn->prepare("INSERT INTO parcels (
            sender_name, sender_address, sender_city, sender_email, sender_phone, sender_pincode,
            receiver_name, receiver_address, receiver_city, receiver_email, receiver_phone, receiver_pincode,
            from_branch_id, to_branch_id, total_amount, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssssssssdd",
            $_POST['sender_name'], $_POST['sender_address'], $_POST['sender_city'],
            $_POST['sender_email'], $_POST['sender_phone'], $_POST['sender_pincode'],
            $_POST['receiver_name'], $_POST['receiver_address'], $_POST['receiver_city'],
            $_POST['receiver_email'], $_POST['receiver_phone'], $_POST['receiver_pincode'],
            $_POST['from_branch'], $_POST['to_branch'],
            $_POST['total_amount'],
            $_SESSION['user_id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Parcel insertion failed: " . $stmt->error);
        }

        $parcel_id = $conn->insert_id;
        $tracking_number = "TRK" . str_pad($parcel_id, 5, "0", STR_PAD_LEFT) . date("mdHi");

        // Update tracking number
        $update_stmt = $conn->prepare("UPDATE parcels SET tracking_number = ? WHERE id = ?");
        $update_stmt->bind_param("si", $tracking_number, $parcel_id);
        $update_stmt->execute();

        // Insert items
        if (!empty($_POST['item_name'])) {
            $items_stmt = $conn->prepare("INSERT INTO parcel_items (parcel_id, item_name, kilograms, price) VALUES (?, ?, ?, ?)");
            
            foreach ($_POST['item_name'] as $index => $item_name) {
                $kg = (float)$_POST['kilograms'][$index];
                $price = $kg * 50;
                
                $items_stmt->bind_param("isdd", $parcel_id, $item_name, $kg, $price);
                $items_stmt->execute();
            }
        }

        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Parcel registered successfully!',
            'tracking_number' => $tracking_number
        ]);
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit();
    }
}

// Show form for GET requests
include 'maindash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .dashboard-content {
            margin-left: 250px;
            padding: 20px;
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
        <h2 class="text-center mb-4">Parcel Registration</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Enter Parcel Details</div>
            <div class="card-body">
            <form id="parcelForm">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Sender Details</h4>
                            <label class="form-label">Name</label>
                            <input type="text" name="sender_name" class="form-control" required>
                            <label class="form-label">Address</label>
                            <input type="text" name="sender_address" class="form-control" required>
                            <label class="form-label">City</label>
                            <input type="text" name="sender_city" class="form-control" required>
                            <label class="form-label">Email</label>
                            <input type="email" name="sender_email" class="form-control" required>
                            <label class="form-label">Phone</label>
                            <input type="text" name="sender_phone" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="sender_pincode" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                        </div>
                        <div class="col-md-6">
                            <h4>Receiver Details</h4>
                            <label class="form-label">Name</label>
                            <input type="text" name="receiver_name" class="form-control" required>
                            <label class="form-label">Address</label>
                            <input type="text" name="receiver_address" class="form-control" required>
                            <label class="form-label">City</label>
                            <input type="text" name="receiver_city" class="form-control" required>
                            <label class="form-label">Email</label>
                            <input type="email" name="receiver_email" class="form-control" required>
                            <label class="form-label">Phone</label>
                            <input type="text" name="receiver_phone" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                                <label class="form-label">Pincode</label>
                            <input type="text" name="receiver_pincode" class="form-control" required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">From Branch</label>
                            <select name="from_branch" id="from_branch" class="form-control select2" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Branch</label>
                            <select name="to_branch" id="to_branch" class="form-control select2" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                    </div>
                    <h4 class="mt-4">Parcel Items</h4>
                    <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Kilograms</th>
                                    <th>Price (₹)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="parcelItems">
                                <tr class="parcel-item">
                                    <td><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></td>
                                    <td><input type="number" name="kilograms[]" class="form-control kg" step="0.1" placeholder="Kilograms" required></td>
                                    <td><input type="text" name="price[]" class="form-control price" placeholder="Price" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success add-item">+</button>
                                        <button type="button" class="btn btn-danger remove-item">X</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    
                    <h5 class="text-end">Total Amount: ₹ <span id="totalAmount" name="total_amount">0</span></h5>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success w-50">Submit Parcel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $(".select2").select2();
    
    // Load branches
    function loadBranches() {
        $.ajax({
            url: "fet_branches.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let options = '<option value="">Select Branch</option>';
                data.forEach(branch => {
                    options += `<option value="${branch.id}">${branch.name}</option>`;
                });
                $("#from_branch, #to_branch").html(options);
            }
        });
    }
    loadBranches();

    // Update total calculation
    function updateTotal() {
        let total = 0;
        $(".parcel-item").each(function () {
            let kg = parseFloat($(this).find(".kg").val()) || 0;
            let price = kg * 50;
            $(this).find(".price").val(price.toFixed(2));
            total += price;
        });
        $("#totalAmount").text(total.toFixed(2));
    }

    // Dynamic item management
    $(document).on("input", ".kg", updateTotal);
    
    $(document).on("click", ".add-item", function () {
        let row = $("#parcelItems tr:first").clone();
        row.find("input").val("");
        $("#parcelItems").append(row);
    });

    $(document).on("click", ".remove-item", function () {
        if ($("#parcelItems tr").length > 1) {
            $(this).closest("tr").remove();
            updateTotal();
        } else {
            alert("At least one parcel item is required.");
        }
    });

    // Form submission
    $("#parcelForm").submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("total_amount", $("#totalAmount").text().trim());

        // Clear existing array values
        formData.delete("item_name[]");
        formData.delete("kilograms[]");
        formData.delete("price[]");

        $(".parcel-item").each(function () {
            formData.append("item_name[]", $(this).find("[name='item_name[]']").val());
            formData.append("kilograms[]", $(this).find("[name='kilograms[]']").val());
            formData.append("price[]", $(this).find("[name='price[]']").val());
        });

        $(".btn-success").prop("disabled", true);

        fetch(window.location.href, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                $("#parcelForm")[0].reset();
                $("#parcelItems").html($("#parcelItems tr:first").clone());
                $("#totalAmount").text("0.00");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while processing your request.");
        })
        .finally(() => {
            $(".btn-success").prop("disabled", false);
        });
    });
});
</script>
</body>
</html>
<?php ob_end_flush(); ?>