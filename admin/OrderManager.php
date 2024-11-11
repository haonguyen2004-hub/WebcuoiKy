<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Set the number of records per page
$records_per_page = 5;

// Get the current page or set default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: OrderManager.php");
    exit();
}

// Calculate total number of records for pagination
$total_records_result = $conn->query("SELECT COUNT(*) AS total FROM orders");
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch the orders for the current page
$sql = "SELECT orders.*, customers.first_name, customers.last_name 
        FROM orders 
        INNER JOIN customers ON orders.customer_id = customers.customer_id 
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

    <!-- Pagination controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
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
