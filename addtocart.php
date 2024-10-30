<?php
session_start();
include 'includes/db_conn.inc';

if (isset($_POST['add_to_cart']) && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $quantity = intval($_POST['quantity']); // Lấy số lượng

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT product_id, product_name, product_image, price FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if (array_key_exists($id, $_SESSION['cart'])) {
            // Nếu có, tăng số lượng
            $_SESSION['cart'][$id]['soluong'] += $quantity;
        } else {
            // Nếu chưa có, thêm sản phẩm mới
            $_SESSION['cart'][$id] = array(
                "product_id" => $row['product_id'],
                "product_name" => $row['product_name'],
                "product_image" => $row['product_image'],
                "price" => $row['price'],
                "soluong" => $quantity
            );
        }

        // Chuyển hướng đến trang giỏ hàng
        header("Location: shoping-cart.php");
        exit();
    } else {
        // Sản phẩm không tồn tại
        header("Location: index.php?error=product_not_found");
        exit();
    }
} else {
    // Không có ID sản phẩm được cung cấp
    header("Location: index.php?error=no_product_specified");
    exit();
}
?>
