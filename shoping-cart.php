<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

// Kiểm tra khách hàng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Cần đăng nhập để thêm vào giỏ hàng');</script>";
    exit;
}

// Lấy customer_id từ session
$customerId = $_SESSION['customer_id'];

// Kiểm tra xem customer_id có phải là một mảng hay không
if (is_array($customerId)) {
    // Nếu customer_id là một mảng, hãy chỉ định một giá trị cụ thể
    $customerId = $customerId[0]; // hoặc xử lý theo cách khác
}

// Sử dụng customerId trong câu truy vấn
$sqlCart = "SELECT cart_id FROM carts WHERE customer_id = ?";
$stmt = mysqli_prepare($conn, $sqlCart);
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$resultCart = mysqli_stmt_get_result($stmt);

if (!$resultCart || mysqli_num_rows($resultCart) === 0) {
    echo "Giỏ hàng của bạn trống.";
    exit;
}

$cart = mysqli_fetch_assoc($resultCart);
$cartId = $cart['cart_id'];

// Lấy sản phẩm trong giỏ hàng
$sqlCartItems = "SELECT ci.cart_item_id, p.product_name, p.product_image, ci.quantity, ci.price, (ci.quantity * ci.price) AS total_price
                 FROM cartitems ci
                 JOIN products p ON ci.product_id = p.product_id
                 WHERE ci.cart_id = $cartId";
$resultCartItems = mysqli_query($conn, $sqlCartItems);

if (!$resultCartItems) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

$totalAmount = 0;
?>

<section class="shoping-cart spad">
    <div class="container">
        <h1 style="margin-bottom: 100px;">Giỏ hàng của bạn</h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Giá tiền</th>
                                <th>Số lượng</th>
                                <th>Đơn Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($resultCartItems)): ?>
                                <tr>
                                    <td class="shoping__cart__item">
                                        <img src="img/product/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="50">
                                        <h5><?php echo htmlspecialchars($item['product_name']); ?></h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <form method="post" action="update_cart.php">
                                                <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                                <div class="pro-qty">
                                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" required>
                                                    
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        <?php echo number_format($item['total_price'], 0, ',', '.'); ?> VNĐ
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <form method="post" action="remove_from_cart.php">
                                            <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                            <button type="submit" class="icon_close" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');"></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $totalAmount += $item['total_price']; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="products.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    <button type="submit" form="cart-form" class="primary-btn cart-btn cart-btn-right">
                        <span class="icon_loading"></span> Cập nhật giỏ hàng
                    </button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Tổng tiền <span><?php echo number_format($totalAmount, 0, ',', '.'); ?> VNĐ</span></li>
                    </ul>
                    <a href="checkout.php" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
