<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Xử lý xóa giảm giá
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM discounts WHERE discount_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: DiscountManager.php");
    exit();
}

// Truy vấn danh sách giảm giá
$discounts_result = $conn->query("SELECT * FROM discounts");
?>

<div class="container mt-5">
    <h1 class="mb-4">Quản lý giảm giá</h1>

    <!-- Nút chuyển đến trang thêm giảm giá -->
    <a href="AddDiscount.php" class="btn btn-primary mb-4">Thêm giảm giá mới</a>

    <!-- Hiển thị danh sách giảm giá -->
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Tên giảm giá</th>
                <th>Loại</th>
                <th>Giá trị giảm</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $discounts_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['discount_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['discount_name']); ?></td>
                    <td><?php echo $row['discount_type'] === 'percentage' ? 'Phần trăm' : 'Cố định'; ?></td>
                    <td><?php echo number_format($row['discount_value'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td>
                        <a href="edit_discount.php?id=<?php echo $row['discount_id']; ?>"
                            class="btn btn-primary btn-sm">Sửa</a>
                        <a href="DiscountManager.php?delete_id=<?php echo $row['discount_id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc muốn xóa giảm giá này?');">Xóa</a>
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