<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Xử lý xóa đơn hàng
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: OrderManager.php");
    exit();
}

// Truy vấn danh sách đơn hàng
$orders_result = $conn->query("SELECT orders.*, customers.first_name, customers.last_name FROM orders INNER JOIN customers ON orders.customer_id = customers.customer_id");
?>

<div class="container mt-5">
    <h1 class="mb-4">Quản lý đơn hàng</h1>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt hàng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo number_format($row['total_amount'], 2); ?> VND</td>
                    <td><?php echo ucfirst($row['order_status']); ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>
                        <a href="OrderDetails.php?id=<?php echo $row['order_id']; ?>" class="btn btn-info btn-sm">Xem chi tiết</a>
                        <a href="OrderManager.php?delete_id=<?php echo $row['order_id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>

