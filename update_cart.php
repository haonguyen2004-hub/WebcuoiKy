
<?php
session_start();

// Kết nối với cơ sở dữ liệu của bạn
include 'includes/db_conn.inc'; // Đảm bảo có kết nối PDO hoặc MySQLi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu $_POST['cart_item_id'] và $_POST['quantity'] tồn tại và là mảng
    if (isset($_POST['cart_item_id']) && isset($_POST['quantity']) &&
        is_array($_POST['cart_item_id']) && is_array($_POST['quantity'])) {

        $cartItemIds = $_POST['cart_item_id'];
        $quantities = $_POST['quantity'];

        // Kiểm tra nếu số lượng các mục trong mảng trùng khớp
        if (count($cartItemIds) === count($quantities)) {
            $errors = [];

            for ($i = 0; $i < count($cartItemIds); $i++) {
                $cartItemId = $cartItemIds[$i];
                $quantity = $quantities[$i];

                // Kiểm tra tính hợp lệ của số lượng
                if ($quantity < 1) {
                    $errors[] = "Số lượng không hợp lệ cho sản phẩm ID: $cartItemId";
                    continue;
                }

                // Cập nhật số lượng cho từng sản phẩm
                $stmt = $conn->prepare("UPDATE cartitems SET quantity = ? WHERE cart_item_id = ?");
                $stmt->bind_param("ii", $quantity, $cartItemId);

                if (!$stmt->execute()) {
                    $errors[] = "Không thể cập nhật số lượng cho sản phẩm ID: $cartItemId";
                }
            }

            // Kiểm tra nếu có lỗi
            if (empty($errors)) {
                echo "Cập nhật giỏ hàng thành công!";
                header("Location: shoping-cart.php");
            } else {
                echo "Có lỗi xảy ra khi cập nhật: " . implode(", ", $errors);
            }
        } else {
            echo "Dữ liệu không hợp lệ.";
        }
    } else {
        echo "Yêu cầu không hợp lệ. Dữ liệu không được gửi đúng định dạng.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
// Quay lại trang giỏ hàng sau khi cập nhật 

?>