<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Xử lý thêm giảm giá
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discount_name = $_POST['discount_name'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Thêm giảm giá vào database
    $sql = "INSERT INTO discounts (discount_name, discount_type, discount_value, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $discount_name, $discount_type, $discount_value, $start_date, $end_date);

    if ($stmt->execute()) {
        header("Location: DiscountManager.php?message=added");
        exit();
    } else {
        echo "Lỗi khi thêm giảm giá.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm giảm giá</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thêm giảm giá mới</h1>
    <form action="" method="post" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="discount_name">Tên giảm giá:</label>
            <input type="text" class="form-control" id="discount_name" name="discount_name" required>
        </div>
        <div class="form-group mb-4">
            <label for="discount_type">Loại giảm giá:</label>
            <select class="form-control" id="discount_type" name="discount_type" required>
                <option value="percentage">Phần trăm</option>
                <option value="fixed">Cố định</option>
            </select>
        </div>
        <div class="form-group mb-4">
            <label for="discount_value">Giá trị giảm:</label>
            <input type="number" class="form-control" id="discount_value" name="discount_value" required>
        </div>
        <div class="form-group mb-4">
            <label for="start_date">Ngày bắt đầu:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group mb-4">
            <label for="end_date">Ngày kết thúc:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm giảm giá</button>
        <a href="DiscountManager.php" class="btn btn-danger">Hủy</a>
    </form>
</div>
<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>
