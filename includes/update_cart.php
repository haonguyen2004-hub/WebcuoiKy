<?php
session_start();
include 'includes/db_conn.inc';

// Tắt thông báo lỗi để ngăn xuất ra ngoài JSON
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

// Xác minh yêu cầu POST và dữ liệu đầu vào hợp lệ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItemId = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

    if ($cartItemId > 0 && $quantity > 0) {
        // Chuẩn bị và thực thi truy vấn cập nhật số lượng
        $stmt = $conn->prepare("UPDATE cartitems SET quantity = ? WHERE cart_item_id = ?");
        $stmt->bind_param("ii", $quantity, $cartItemId);

        if ($stmt->execute()) {
            // Tính tổng tiền mới sau khi cập nhật
            $stmtTotal = $conn->prepare("SELECT SUM(quantity * price) AS total_amount FROM cartitems WHERE cart_id = ?");
            $stmtTotal->bind_param("i", $_SESSION['cart_id']);
            $stmtTotal->execute();
            $resultTotal = $stmtTotal->get_result();
            $totalAmount = $resultTotal->fetch_assoc()['total_amount'];

            // Trả về JSON hợp lệ cho AJAX
            echo json_encode([
                'status' => 'success',
                'item_total' => number_format($quantity * $price, 0, ',', '.'),
                'total_amount' => number_format($totalAmount, 0, ',', '.')
            ]);
            exit;
        } else {
            // Trả về lỗi JSON nếu cập nhật không thành công
            echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật giỏ hàng.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng không hợp lệ.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
    exit;
}
