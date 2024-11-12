<?php
ob_start();
session_start();
include 'includes/db_conn.inc'; // Kết nối tới cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$total_amount = 0;

// Truy vấn lấy thông tin giỏ hàng của khách hàng dựa trên customer_id và kiểm tra giảm giá
$query = "
    SELECT p.product_name, p.price, ci.quantity, d.discount_type, d.discount_value 
    FROM cartitems ci 
    JOIN carts c ON ci.cart_id = c.cart_id
    JOIN products p ON ci.product_id = p.product_id
    LEFT JOIN productdiscounts pd ON p.product_id = pd.product_id
    LEFT JOIN discounts d ON pd.discount_id = d.discount_id
    AND d.start_date <= CURDATE() AND d.end_date >= CURDATE()
    WHERE c.customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC); // Lưu giỏ hàng vào mảng $cart_items
?>

<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Thông Tin Thanh Toán</h4>
            <form action="includes/place_order.php" method="POST">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <!-- Thông tin khách hàng -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Họ<span>*</span></p>
                                    <input type="text" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Tên<span>*</span></p>
                                    <input type="text" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Địa Chỉ<span>*</span></p>
                            <input type="text" name="shipping_address" placeholder="Địa chỉ" required>
                        </div>
                        <div class="checkout__input">
                            <p>Thành Phố<span>*</span></p>
                            <input type="text" name="shipping_city" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số Điện Thoại<span>*</span></p>
                                    <input type="text" name="shipping_phone" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email<span>*</span></p>
                                    <input type="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Ghi Chú Đơn Hàng</p>
                            <input type="text" name="order_notes" placeholder="Ghi chú về đơn hàng.">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn Hàng Của Bạn</h4>
                            <div class="checkout__order__products">Sản Phẩm <span>Tổng</span></div>
                            <ul>
                                <?php foreach ($cart_items as $item): ?>
                                    <?php
                                    // Kiểm tra xem sản phẩm có giảm giá không và tính giá đã giảm (nếu có)
                                    $item_price = $item['price'];
                                    if (!is_null($item['discount_type'])) {
                                        if ($item['discount_type'] == 'percentage') {
                                            $item_price = $item['price'] * (1 - $item['discount_value'] / 100);
                                        } elseif ($item['discount_type'] == 'fixed') {
                                            $item_price = max(0, $item['price'] - $item['discount_value']);
                                        }
                                    }
                                    $item_total = $item_price * $item['quantity']; // Tổng tiền của sản phẩm sau khi giảm (nếu có)
                                    $total_amount += $item_total; // Cộng vào tổng tiền giỏ hàng
                                    ?>
                                    <li>
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                        (x<?php echo $item['quantity']; ?>)
                                        <span><?php echo number_format($item_total, 0, ',', '.'); ?> VND</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="checkout__order__total">Tổng Cộng
                                <span><?php echo number_format($total_amount, 0, ',', '.'); ?> VND</span>
                            </div>
                            <div class="checkout__input__checkbox">
                                <label for="payment">
                                    Thanh Toán Khi Nhận Hàng
                                    <input type="radio" name="payment_method" value="Thanh Toán Khi Nhận Hàng" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="checkout__input__checkbox">
                                <label for="paypal">
                                    Momo
                                    <input type="radio" name="payment_method" value="Momo">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <button type="submit" class="site-btn">ĐẶT HÀNG</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>