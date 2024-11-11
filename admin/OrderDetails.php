<?php
session_start();
ob_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Kiểm tra và lấy ID đơn hàng từ URL
if (!isset($_GET['id'])) {
    header("Location: OrderManager.php");
    exit();
}

$order_id = $_GET['id'];

// Truy vấn thông tin đơn hàng và khách hàng
$order_sql = "SELECT orders.*, customers.first_name, customers.last_name 
              FROM orders 
              INNER JOIN customers ON orders.customer_id = customers.customer_id 
              WHERE order_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Đơn hàng không tồn tại!";
    exit();
}

// Truy vấn chi tiết sản phẩm trong đơn hàng
$order_details_sql = "SELECT orderdetails.*, products.product_name 
                      FROM orderdetails 
                      INNER JOIN products ON orderdetails.product_id = products.product_id 
                      WHERE order_id = ?";
$stmt = $conn->prepare($order_details_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_details_result = $stmt->get_result();

// Cập nhật trạng thái đơn hàng nếu có yêu cầu từ form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['order_status'];
    $update_status_sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_status_sql);
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    header("Location: OrderDetails.php?id=" . $order_id . "&message=status_updated");
    exit();
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Chi tiết đơn hàng #<?php echo $order['order_id']; ?></h1>

    <div class="mb-4">
        <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
        <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount'], 2); ?> VND</p>
        <p><strong>Ngày đặt hàng:</strong> <?php echo $order['order_date']; ?></p>
        <p><strong>Trạng thái hiện tại:</strong> <?php echo ucfirst($order['order_status']); ?></p>
    </div>

    <!-- Form cập nhật trạng thái đơn hàng -->
    <form method="post" class="mb-4">
        <div class="form-group mb-3">
            <label for="order_status" class="mb-3">Cập nhật trạng thái đơn hàng:</label>
            <select name="order_status" id="order_status" class="form-control">
                <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                <option value="completed" <?php echo $order['order_status'] === 'completed' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
        </div>
        <button type="submit" name="update_status" class="btn btn-primary">Cập nhật trạng thái</button>
        <a href="OrderManager.php" class="btn btn-secondary">Quay lại</a>
    </form>

    <!-- Bảng hiển thị chi tiết sản phẩm trong đơn hàng -->
    <h3>Sản phẩm trong đơn hàng</h3>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $order_details_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 0); ?> VNĐ </td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 0); ?> VNĐ </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>

