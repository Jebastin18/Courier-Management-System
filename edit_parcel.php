<?php
include 'db.php';
include 'maindash.php';
$parcel_id = $_GET['id'] ?? 0;
$parcel = [];
if ($parcel_id) {
    $stmt = $conn->prepare("SELECT * FROM parcels WHERE id = ?");
    $stmt->bind_param("i", $parcel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $parcel = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parcel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h2 class="text-center mb-4">Edit Parcel</h2>
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">Update Parcel Details</div>
            <div class="card-body">
                <form id="editParcelForm">
                    <input type="hidden" name="parcel_id" value="<?= $parcel_id ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Sender Details</h4>
                            <label class="form-label">Name</label>
                            <input type="text" name="sender_name" class="form-control" value="<?= $parcel['sender_name'] ?? '' ?>" required>
                            <label class="form-label">Address</label>
                            <input type="text" name="sender_address" class="form-control" value="<?= $parcel['sender_address'] ?? '' ?>" required>
                            <label class="form-label">City</label>
                            <input type="text" name="sender_city" class="form-control" value="<?= $parcel['sender_city'] ?? '' ?>" required>
                            <label class="form-label">Email</label>
                            <input type="email" name="sender_email" class="form-control" value="<?= $parcel['sender_email'] ?? '' ?>" required>
                            <label class="form-label">Phone</label>
                            <input type="text" name="sender_phone" class="form-control" value="<?= $parcel['sender_phone'] ?? '' ?>" required>
                            <label class="form-label">Pincode</label>
                            <input type="text" name="sender_pincode" class="form-control" value="<?= $parcel['sender_pincode'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <h4>Receiver Details</h4>
                            <label class="form-label">Name</label>
                            <input type="text" name="receiver_name" class="form-control" value="<?= $parcel['receiver_name'] ?? '' ?>" required>
                            <label class="form-label">Address</label>
                            <input type="text" name="receiver_address" class="form-control" value="<?= $parcel['receiver_address'] ?? '' ?>" required>
                            <label class="form-label">City</label>
                            <input type="text" name="receiver_city" class="form-control" value="<?= $parcel['receiver_city'] ?? '' ?>" required>
                            <label class="form-label">Email</label>
                            <input type="email" name="receiver_email" class="form-control" value="<?= $parcel['receiver_email'] ?? '' ?>" required>
                            <label class="form-label">Phone</label>
                            <input type="text" name="receiver_phone" class="form-control" value="<?= $parcel['receiver_phone'] ?? '' ?>" required>
                            <label class="form-label">Pincode</label>
                            <input type="text" name="receiver_pincode" class="form-control" value="<?= $parcel['receiver_pincode'] ?? '' ?>" required>
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
                        </tbody>
                    </table>
                    <h5 class="text-end">Total Amount<span id="totalAmount">0.00</span></h5>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success w-50">Update Parcel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        const parcelId = <?= $parcel_id ?>; // Get Parcel ID from PHP
        if (parcelId) {
            $.ajax({
                url: "fetch_parsel.php",
                type: "GET",
                data: { id: parcelId },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        let parcel = response.data;
                        $("input[name='sender_name']").val(parcel.sender_name);
                        $("input[name='sender_address']").val(parcel.sender_address);
                        $("input[name='sender_city']").val(parcel.sender_city);
                        $("input[name='sender_email']").val(parcel.sender_email);
                        $("input[name='sender_phone']").val(parcel.sender_phone);
                        $("input[name='sender_pincode']").val(parcel.sender_pincode);
                        $("input[name='receiver_name']").val(parcel.receiver_name);
                        $("input[name='receiver_address']").val(parcel.receiver_address);
                        $("input[name='receiver_city']").val(parcel.receiver_city);
                        $("input[name='receiver_email']").val(parcel.receiver_email);
                        $("input[name='receiver_phone']").val(parcel.receiver_phone);
                        $("input[name='receiver_pincode']").val(parcel.receiver_pincode);
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching parcel details:", error); 
                    alert("Error fetching parcel details. Please try again.");
                }
            });
        }
        if (parcelId) {
            $.ajax({
                url: "fetch_parcel_item.php",
                type: "GET",
                data: { id: parcelId },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        let items = response.items;
                        let itemHtml = "";
                        let totalAmount = 0;
                        items.forEach((item) => {
                            totalAmount += parseFloat(item.price);
                            itemHtml += `
                                <tr class="parcel-item">
                                    <td><input type="text" name="item_name[]" class="form-control" value="${item.item_name}" required></td>
                                    <td><input type="number" name="kilograms[]" class="form-control kg" step="0.1" value="${item.kilograms}" required></td>
                                    <td><input type="text" name="price[]" class="form-control price" value="${item.price}" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success add-item">+</button>
                                        <button type="button" class="btn btn-danger remove-item">X</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $("#parcelItems").html(itemHtml); 
                        $("#totalAmount").text(totalAmount.toFixed(2)); 
                    } else {
                        alert(response.message); 
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching parcel items:", error); 
                    alert("Error fetching parcel items. Please try again.");
                }
            });
        }
        $("#editParcelForm").submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);     
            let isValid = true;
            $("input[required]").each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass("is-invalid");
                } else {
                    $(this).removeClass("is-invalid");
                }
            });
            if (!isValid) {
                alert("Please fill all required fields.");
                return;
            }
            fetch("update_parcel.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Show success or error message
                if (data.status === "success") {
                    window.location.href = "parsel_report.php"; 
                }
            })
            .catch(error => {
                console.error("Error:", error); 
                alert("An error occurred. Please try again.");
            });
        });
        $(document).on("click", ".add-item", function () {
            let rowHtml = `
                <tr class="parcel-item">
                    <td><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></td>
                    <td><input type="number" name="kilograms[]" class="form-control kg" step="0.1" placeholder="Kilograms" required></td>
                    <td><input type="text" name="price[]" class="form-control price" placeholder="Price" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success add-item">+</button>
                        <button type="button" class="btn btn-danger remove-item">X</button>
                    </td>
                </tr>
            `;
            $("#parcelItems").append(rowHtml); 
        });
        $(document).on("click", ".remove-item", function () {
            $(this).closest(".parcel-item").remove(); 
            calculateTotal(); 
        });
        $(document).on("input", ".kg", function () {
            let kg = parseFloat($(this).val()) || 0;
            let pricePerKg = 50; 
            let price = kg * pricePerKg;
            $(this).closest("tr").find(".price").val(price.toFixed(2)); 
            calculateTotal(); 
        });
        function calculateTotal() {
            let total = 0;
            $(".price").each(function () {
                total += parseFloat($(this).val()) || 0;
            });
            $("#totalAmount").text(total.toFixed(2)); 
        }
    });
</script>
</body>
</html>