<?php
session_start();
include 'db_conn.inc';

header('Content-Type: application/json');

if (!isset($_POST['cart_item_id']) || !isset($_POST['quantity'])) {
    echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ."]);
    exit;
}

$cartItemId = (int) $_POST['cart_item_id'];
$quantity = (int) $_POST['quantity'];

if ($quantity < 1) {
    echo json_encode(["success" => false, "message" => "Số lượng không hợp lệ."]);
    exit;
}

// Cập nhật số lượng trong cơ sở dữ liệu
$sqlUpdateQuantity = "UPDATE cartitems SET quantity = ? WHERE cart_item_id = ?";
$stmtUpdateQuantity = mysqli_prepare($conn, $sqlUpdateQuantity);
mysqli_stmt_bind_param($stmtUpdateQuantity, "ii", $quantity, $cartItemId);

if (mysqli_stmt_execute($stmtUpdateQuantity)) {
    // Lấy tổng giá mới cho sản phẩm và giỏ hàng
    $sqlTotalPrice = "SELECT quantity * price AS total_price FROM cartitems WHERE cart_item_id = ?";
    $stmtTotalPrice = mysqli_prepare($conn, $sqlTotalPrice);
    mysqli_stmt_bind_param($stmtTotalPrice, "i", $cartItemId);
    mysqli_stmt_execute($stmtTotalPrice);
    mysqli_stmt_bind_result($stmtTotalPrice, $newTotalPrice);
    mysqli_stmt_fetch($stmtTotalPrice);

    // Lấy tổng giá trị mới của giỏ hàng
    $sqlCartTotal = "SELECT SUM(quantity * price) AS cart_total FROM cartitems WHERE cart_id = (SELECT cart_id FROM cartitems WHERE cart_item_id = ?)";
    $stmtCartTotal = mysqli_prepare($conn, $sqlCartTotal);
    mysqli_stmt_bind_param($stmtCartTotal, "i", $cartItemId);
    mysqli_stmt_execute($stmtCartTotal);
    mysqli_stmt_bind_result($stmtCartTotal, $newCartTotal);
    mysqli_stmt_fetch($stmtCartTotal);

    // Đóng các câu lệnh
    mysqli_stmt_close($stmtTotalPrice);
    mysqli_stmt_close($stmtCartTotal);

    echo json_encode([
        "success" => true,
        "newTotalPrice" => number_format($newTotalPrice, 0, ',', '.'),
        "newCartTotal" => number_format($newCartTotal, 0, ',', '.')
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật giỏ hàng."]);
}

mysqli_stmt_close($stmtUpdateQuantity);
mysqli_close($conn);
?>