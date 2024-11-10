<?php
session_start();
ob_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Lấy ID của giảm giá từ URL
if (!isset($_GET['id'])) {
    header("Location: DiscountManager.php");
    exit();
}

$discount_id = $_GET['id'];

// Truy vấn thông tin giảm giá hiện tại từ cơ sở dữ liệu
$sql = "SELECT * FROM discounts WHERE discount_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $discount_id);
$stmt->execute();
$result = $stmt->get_result();
$discount = $result->fetch_assoc();

if (!$discount) {
    echo "Giảm giá không tồn tại!";
    exit();
}

// Xử lý cập nhật giảm giá
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discount_name = $_POST['discount_name'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Cập nhật giảm giá trong cơ sở dữ liệu
    $sql = "UPDATE discounts SET discount_name = ?, discount_type = ?, discount_value = ?, start_date = ?, end_date = ? WHERE discount_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $discount_name, $discount_type, $discount_value, $start_date, $end_date, $discount_id);

    if ($stmt->execute()) {
        header("Location: DiscountManager.php?message=updated");
        exit();
    } else {
        echo "Lỗi khi cập nhật giảm giá.";
    }
}
?>


<div class="container mt-5">
    <h1 class="mb-4">Sửa giảm giá</h1>
    <form action="" method="post" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="discount_name">Tên giảm giá:</label>
            <input type="text" class="form-control" id="discount_name" name="discount_name" value="<?php echo htmlspecialchars($discount['discount_name']); ?>" required>
        </div>
        <div class="form-group mb-4">
            <label for="discount_type">Loại giảm giá:</label>
            <select class="form-control" id="discount_type" name="discount_type" required>
                <option value="percentage" <?php echo $discount['discount_type'] == 'percentage' ? 'selected' : ''; ?>>Phần trăm</option>
                <option value="fixed" <?php echo $discount['discount_type'] == 'fixed' ? 'selected' : ''; ?>>Cố định</option>
            </select>
        </div>
        <div class="form-group mb-4">
            <label for="discount_value">Giá trị giảm:</label>
            <input type="number" class="form-control" id="discount_value" name="discount_value" value="<?php echo $discount['discount_value']; ?>" required>
        </div>
        <div class="form-group mb-4">
            <label for="start_date">Ngày bắt đầu:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $discount['start_date']; ?>" required>
        </div>
        <div class="form-group mb-4">
            <label for="end_date">Ngày kết thúc:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $discount['end_date']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="DiscountManager.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>
