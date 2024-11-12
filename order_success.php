<?php
session_start();
ob_start();
include 'includes/db_conn.inc'; // Kết nối tới cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Lấy thông tin đơn hàng gần nhất của khách hàng
$query = "
    SELECT o.order_id, o.total_amount, o.order_date, o.shipping_address, o.shipping_city, o.shipping_phone, o.notes, o.payment_method
    FROM orders o
    WHERE o.customer_id = ? 
    ORDER BY o.order_date DESC
    LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Kiểm tra nếu không tìm thấy đơn hàng nào
if (!$order) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

// Thông tin sản phẩm trong đơn hàng
$productQuery = "
    SELECT p.product_name, od.price, od.quantity
    FROM orderdetails od
    JOIN products p ON od.product_id = p.product_id
    WHERE od.order_id = ?";
$productStmt = $conn->prepare($productQuery);
$productStmt->bind_param("i", $order['order_id']);
$productStmt->execute();
$productResult = $productStmt->get_result();
$products = $productResult->fetch_all(MYSQLI_ASSOC);
?>

<div class="card mb-5">
    <div class="title">Hóa Đơn Mua Hàng</div>
    <div class="info">
        <div class="row">
            <div class="col-9">
                <span id="heading">Ngày Đặt Hàng</span><br>
                <span id="details"><?php echo htmlspecialchars($order['order_date']); ?></span>
            </div>
            <div class="col-3 pull-right">
                <span id="heading">Mã Đơn Hàng</span><br>
                <span id="details">#<?php echo htmlspecialchars($order['order_id']); ?></span>
            </div>
        </div>
    </div>

    <div class="pricing">
        <?php foreach ($products as $product): ?>
            <div class="row">
                <div class="col-9">
                    <span id="name"><?php echo htmlspecialchars($product['product_name']); ?> (Số lượng:
                        <?php echo $product['quantity']; ?>)</span>
                </div>
                <div class="col-3">
                    <span id="price"><?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?>
                        VND</span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row">
            <div class="col-9">
                <span id="name">Phương Thức Thanh Toán</span>
            </div>
            <div class="col-3">
                <span id="price"><?php echo htmlspecialchars($order['payment_method'] ?? 'Chuyển khoản'); ?></span>
            </div>
        </div>
    </div>
    <div class="notes">
        <div class="row">
            <div class="col-12">
                <span>Ghi Chú Đơn Hàng</span><br>
                <p id="details"><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
            </div>
        </div>
    </div>

    <div class="total">
        <div class="row">
            <div class="col-9">Tổng Cộng</div>
            <div class="col-3"><big><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</big></div>
        </div>
    </div>

    <div class="total">
        <div class="row">
            <div class="col-9">Khách Phải Trả</div>
            <div class="col-3"><big><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</big></div>
        </div>
    </div>
</div>

<style>
    body {
        font-size: 14px;
    }

    .card {
        margin: auto;
        width: 38%;
        max-width: 600px;
        padding: 4vh 0;
        box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-top: 3px solid #9e503a;
        border-bottom: 3px solid #9e503a;
    }

    .title {
        color: #9e503a;
        font-weight: 600;
        margin-bottom: 2vh;
        padding: 0 8%;
    }

    .info {
        padding: 5% 8%;
    }

    #heading {
        color: grey;
        line-height: 6vh;
    }

    .pricing {
        background-color: #ddd3;
        padding: 2vh 8%;
        font-weight: 400;
        line-height: 2.5;
    }

    .total {
        padding: 2vh 8%;
        color: #9e503a;
        font-weight: bold;
    }

    .notes {
        padding: 2vh 8%;
        color: #333;
    }

    .footer {
        padding: 0 8%;
        font-size: x-small;
        color: black;
    }
</style>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>