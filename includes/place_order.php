<?php
session_start();
include 'db_conn.inc';

$customer_id = $_SESSION['customer_id'];
$order_success = false;

// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = $_POST['shipping_address'];
    $shipping_city = $_POST['shipping_city'];
    $shipping_phone = $_POST['shipping_phone'];
    $order_notes = isset($_POST['order_notes']) ? $_POST['order_notes'] : ''; // Lấy ghi chú từ form
    $total_amount = 0;

    // Truy vấn để lấy thông tin giỏ hàng và tính tổng tiền
    $query = "
        SELECT p.product_id, p.price, ci.quantity 
        FROM cartitems ci 
        JOIN carts c ON ci.cart_id = c.cart_id
        JOIN products p ON ci.product_id = p.product_id
        WHERE c.customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($cart_items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Lưu đơn hàng vào bảng `orders` với địa chỉ giao hàng và ghi chú
    $stmt = $conn->prepare("
        INSERT INTO orders (customer_id, total_amount, order_status, order_date, shipping_address, shipping_city, shipping_phone, notes) 
        VALUES (?, ?, 'processing', NOW(), ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $customer_id, $total_amount, $shipping_address, $shipping_city, $shipping_phone, $order_notes);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Lưu chi tiết đơn hàng
        $stmt_detail = $conn->prepare("INSERT INTO orderdetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt_detail->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt_detail->execute();

            // Trừ số lượng sản phẩm trong kho
            $stmt_update_stock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
            $stmt_update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt_update_stock->execute();
        }

        // Xóa giỏ hàng
        $stmt_clear_cart = $conn->prepare("
            DELETE ci FROM cartitems ci 
            JOIN carts c ON ci.cart_id = c.cart_id 
            WHERE c.customer_id = ?");
        $stmt_clear_cart->bind_param("i", $customer_id);
        $stmt_clear_cart->execute();

        $order_success = true;
    }
}

if ($order_success) {
    header("Location: ../order_success.php");
    exit();
} else {
    echo "Đã xảy ra lỗi khi xử lý đơn hàng. Vui lòng thử lại.";
}
?>