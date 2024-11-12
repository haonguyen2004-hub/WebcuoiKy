<?php
session_start();
include 'db_conn.inc';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['cart_item_id'])) {
    header("Location: cart.php"); // Chuyển hướng nếu không có `cart_item_id`
    exit();
}

$cart_item_id = intval($_GET['cart_item_id']);

// Xóa sản phẩm khỏi giỏ hàng
$query = "DELETE FROM cartitems WHERE cart_item_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cart_item_id);
$stmt->execute();

// Quay lại trang giỏ hàng sau khi xóa
header("Location: ../shoping-cart.php");
exit();
