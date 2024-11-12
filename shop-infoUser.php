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

// Lấy thông tin khách hàng, bao gồm họ tên đầy đủ
$sqlCustomer = "SELECT first_name, last_name, email FROM customers WHERE customer_id = ?";
$stmtCustomer = $conn->prepare($sqlCustomer);
$stmtCustomer->bind_param("i", $customer_id);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();
$customer = $resultCustomer->fetch_assoc();

// Tính tổng tiền từ các đơn hàng đã mua
$sqlTotalAmount = "SELECT SUM(total_amount) AS total_spent FROM orders WHERE customer_id = ? AND order_status = 'completed'";
$stmtTotal = $conn->prepare($sqlTotalAmount);
$stmtTotal->bind_param("i", $customer_id);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalSpent = $resultTotal->fetch_assoc()['total_spent'] ?? 0;

// Đếm số lượng đơn hàng đã hoàn thành
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
?>


<section class="bobyinfo">
    <div class="profile-container">
        <div class="d-flex align-items-center profile-header mb-4">
            <img src="img/avatar.png" alt="Avatar">
            <div class="ms-3 profile-info fs-4">
                <h3><?php echo $fullName; ?></h3>
                <p><?php echo htmlspecialchars($customer['email']); ?></p>
            </div>
        </div>

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

        <!-- Form -->
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
        padding: 20px;
    }

    .profile-header img {
        border-radius: 50%;
        width: 130px;
        height: 130px;
    }

    .profile-info h5,
    .profile-info p {
        margin-bottom: 0;
    }

    .form-control {
        background-color: #f6f6f6;
        border: none;
        color: #000000;
        height: 50px;
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
</style>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>