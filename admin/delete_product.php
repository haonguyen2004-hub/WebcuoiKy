<?php
include '../includes/db_conn.inc'; // Kết nối tới database

// Kiểm tra xem id của sản phẩm có được gửi qua GET không
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Chuẩn bị câu lệnh xóa sản phẩm
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng về trang quản lý sản phẩm với thông báo thành công
        header("Location: index.php?message=deleted");
        exit();
    } else {
        echo "Lỗi khi xóa sản phẩm.";
    }
} else {
    // Nếu không có ID, chuyển hướng về trang quản lý sản phẩm
    header("Location: index.php");
    exit();
}
?>