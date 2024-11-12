<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Số lượng bản ghi trên mỗi trang
$records_per_page = 5;

// Lấy trang hiện tại hoặc đặt mặc định là 1
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Xử lý thao tác xóa đơn hàng
if (isset($_GET['delete_id'])) {
    $delete_id = filter_var($_GET['delete_id'], FILTER_VALIDATE_INT);
    if ($delete_id) {
        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        header("Location: OrderManager.php");
        exit();
    }
}

// Tính tổng số bản ghi để phân trang
$total_records_result = $conn->query("SELECT COUNT(*) AS total FROM orders");
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Lấy thông tin đơn hàng cho trang hiện tại
$sql = "SELECT orders.*, customers.first_name, customers.last_name 
        FROM orders 
        INNER JOIN customers ON orders.customer_id = customers.customer_id 
        ORDER BY order_date DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $records_per_page);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<div class="container mt-5">
    <h1 class="mb-4">Quản lý đơn hàng</h1>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Địa chỉ giao hàng</th>
                <th>Thành phố</th>
                <th>Số điện thoại</th>
                <th>Ghi chú</th>
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
                    <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                    <td><?php echo htmlspecialchars($row['shipping_city']); ?></td>
                    <td><?php echo htmlspecialchars($row['shipping_phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    <td><?php echo number_format($row['total_amount'], 0, ',', '.'); ?></td>
                    <td><?php echo ucfirst($row['order_status']); ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>
                        <a href="OrderDetails.php?id=<?php echo $row['order_id']; ?>" class="btn btn-primary btn-sm">Xem chi
                            tiết</a>
                        <a href="OrderManager.php?delete_id=<?php echo $row['order_id']; ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Điều khiển phân trang -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Trước</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Tiếp theo</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>