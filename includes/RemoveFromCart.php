<?php
session_start();
include 'db_conn.inc';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['customer_id'])) {
    echo "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
    exit;
}

// Kiểm tra nếu nhận được `cart_item_id`
if (!isset($_POST['cart_item_id'])) {
    echo "<script>alert('Không có sản phẩm nào để xóa');</script>";
    header("Location: cart.php");  // Quay lại trang giỏ hàng
    exit;
}

$cartItemId = (int)$_POST['cart_item_id'];

// Xóa sản phẩm khỏi giỏ hàng trong cơ sở dữ liệu
$sqlDelete = "DELETE FROM cartitems WHERE cart_item_id = ?";
$stmtDelete = mysqli_prepare($conn, $sqlDelete);
mysqli_stmt_bind_param($stmtDelete, "i", $cartItemId);
mysqli_stmt_execute($stmtDelete);

// Kiểm tra nếu xóa thành công
if (mysqli_stmt_affected_rows($stmtDelete) > 0) {
    echo "<script>alert('Đã xóa sản phẩm khỏi giỏ hàng');</script>";
} else {
    echo "<script>alert('Lỗi: Không thể xóa sản phẩm');</script>";
}

// Điều hướng trở lại trang giỏ hàng
header("Location: ../shoping-cart.php");
exit;
?>
