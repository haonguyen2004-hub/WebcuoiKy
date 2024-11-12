<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

// Kiểm tra xem khách hàng đã đăng nhập chưa
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$message = "";

// Xử lý cập nhật mật khẩu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Kiểm tra mật khẩu mới
    if ($new_password !== $confirm_password) {
        $message = "Mật khẩu xác nhận không khớp!";
    } elseif (strlen($new_password) < 6) {
        $message = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    } else {
        // Cập nhật mật khẩu mới không mã hóa
        $sqlUpdatePassword = "UPDATE customers SET password = ? WHERE customer_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdatePassword);
        $stmtUpdate->bind_param("si", $new_password, $customer_id);

        if ($stmtUpdate->execute()) {
            $message = "Mật khẩu đã được cập nhật thành công!";
        } else {
            $message = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
}

// Lấy thông tin khách hàng
$sqlCustomer = "SELECT first_name, last_name, email FROM customers WHERE customer_id = ?";
$stmtCustomer = $conn->prepare($sqlCustomer);
$stmtCustomer->bind_param("i", $customer_id);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();
$customer = $resultCustomer->fetch_assoc();

// Tổng tiền từ các đơn hàng đã mua
$sqlTotalAmount = "SELECT SUM(total_amount) AS total_spent FROM orders WHERE customer_id = ? AND order_status = 'completed'";
$stmtTotal = $conn->prepare($sqlTotalAmount);
$stmtTotal->bind_param("i", $customer_id);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalSpent = $resultTotal->fetch_assoc()['total_spent'] ?? 0;

// Đếm số lượng đơn hàng
$sqlOrderCount = "SELECT COUNT(*) AS order_count FROM orders WHERE customer_id = ?";
$stmtOrderCount = $conn->prepare($sqlOrderCount);
$stmtOrderCount->bind_param("i", $customer_id);
$stmtOrderCount->execute();
$resultOrderCount = $stmtOrderCount->get_result();
$orderCount = $resultOrderCount->fetch_assoc()['order_count'];

// Đếm số lượng đánh giá của khách hàng
$sqlReviewCount = "SELECT COUNT(*) AS review_count FROM product_reviews WHERE customer_id = ?";
$stmtReviewCount = $conn->prepare($sqlReviewCount);
$stmtReviewCount->bind_param("i", $customer_id);
$stmtReviewCount->execute();
$resultReviewCount = $stmtReviewCount->get_result();
$reviewCount = $resultReviewCount->fetch_assoc()['review_count'] ?? 0;

// Họ tên đầy đủ của khách hàng
$fullName = htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']);

// Hàm dịch trạng thái đơn hàng
function translateOrderStatus($status)
{
    switch ($status) {
        case 'pending':
            return 'Đang chờ xử lý';
        case 'processing':
            return 'Đang xử lý';
        case 'completed':
            return 'Hoàn thành';
        case 'cancelled':
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}
?>

<section class="bobyinfo">
    <div class="profile-container">
        <!-- Thông tin người dùng -->
        <div class="d-flex align-items-center profile-header mb-4">
            <img src="img/avatar.png" alt="Avatar">
            <div class="ms-3 profile-info fs-4">
                <h3><?php echo $fullName; ?></h3>
                <p><?php echo htmlspecialchars($customer['email']); ?></p>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-flex justify-content-between text-center stats mb-4 fs-4">
            <div>
                <h4><?php echo $orderCount; ?></h4>
                <p>Đơn hàng</p>
            </div>
            <div>
                <h4><?php echo $reviewCount; ?></h4>
                <p>Đánh giá</p>
            </div>
            <div>
                <h4><?php echo number_format($totalSpent, 0, ',', '.'); ?> VNĐ</h4>
                <p>Tổng tiền</p>
            </div>
        </div>

        <!-- Form cập nhật mật khẩu -->
        <form method="POST">
            <div class="mb-3">
                <label for="displayName" class="form-label fs-4">Tên hiển thị</label>
                <input type="text" class="form-control fs-4" id="displayName" name="display_name"
                    value="<?php echo $fullName; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fs-4">Email</label>
                <input type="email" class="form-control fs-4" id="email" name="email"
                    value="<?php echo htmlspecialchars($customer['email']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label fs-4">Mật khẩu mới</label>
                <input type="password" class="form-control fs-4" id="newPassword" name="new_password">
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label fs-4">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control fs-4" id="confirmPassword" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-danger w-100 btn-lg fs-4">Lưu thay đổi</button>
        </form>
        <p class="text-center mt-4"><?php echo $message; ?></p>

        <!-- Lịch sử đơn hàng -->
        <div class="mt-5">
            <h4 class="text-center">Lịch sử đơn hàng của bạn</h4>
            <div class="table-responsive">
                <table class="table table-striped order-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlOrders = "SELECT order_id, order_date, total_amount, order_status FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
                        $stmtOrders = $conn->prepare($sqlOrders);
                        $stmtOrders->bind_param("i", $customer_id);
                        $stmtOrders->execute();
                        $resultOrders = $stmtOrders->get_result();

                        if ($resultOrders->num_rows > 0):
                            while ($order = $resultOrders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($order['order_date'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</td>
                                    <td><?php echo translateOrderStatus($order['order_status']); ?></td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .bobyinfo {
        background-color: #fff;
        color: #000000;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .profile-container {
        background-color: #ffffff;
        border-radius: 8px;
        width: 100%;
        max-width: 1000px;
    }

    .profile-header img {
        border-radius: 50%;
        width: 130px;
        height: 130px;
    }

    .profile-info h5 {
        margin-bottom: 0;
    }

    .profile-info p {
        color: #000000;
        margin-bottom: 0;
    }

    .form-control {
        background-color: #f6f6f6;
        border: none;
        color: #000000;
        height: 50px;
    }

    .form-control::placeholder {
        color: #646464;
    }

    .btn-save {
        background-color: #d73a4a;
        border: none;
    }

    .stats div {
        text-align: center;
    }

    .stats div h5 {
        font-weight: bold;
    }

    .order-table th,
    .order-table td {
        font-size: 1.2em;
    }

    .order-table {
        font-size: 16px;
        /* Tăng kích thước chữ */
    }

    .order-table th {
        font-weight: bold;
        font-size: 18px;
        /* Tăng kích thước chữ cho tiêu đề bảng */
    }

    .order-table td {
        font-size: 16px;
        /* Tăng kích thước chữ cho dữ liệu bảng */
    }
</style>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>