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
    $customerId = $customerId[0];
}

// Sử dụng customerId trong câu truy vấn
$sqlCart = "SELECT cart_id FROM carts WHERE customer_id = ?";
$stmt = mysqli_prepare($conn, $sqlCart);
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$resultCart = mysqli_stmt_get_result($stmt);

if (!$resultCart || mysqli_num_rows($resultCart) === 0) {
    echo "<p class='text-center mt-5'>Giỏ hàng của bạn trống.</p>";
    exit;
}

$cart = mysqli_fetch_assoc($resultCart);
$cartId = $cart['cart_id'];

// Lấy sản phẩm trong giỏ hàng
$sqlCartItems = "SELECT ci.cart_item_id, p.product_name, p.product_image, ci.quantity, ci.price, (ci.quantity * ci.price) AS total_price
                 FROM cartitems ci
                 JOIN products p ON ci.product_id = p.product_id
                 WHERE ci.cart_id = ?";
$stmtItems = mysqli_prepare($conn, $sqlCartItems);
mysqli_stmt_bind_param($stmtItems, "i", $cartId);
mysqli_stmt_execute($stmtItems);
$resultCartItems = mysqli_stmt_get_result($stmtItems);

if (!$resultCartItems) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

$totalAmount = 0;
?>

<section class="shoping-cart spad">
    <div class="container">
        <h1 style="margin-bottom: 100px;">Giỏ hàng của bạn</h1>

        <?php if (mysqli_num_rows($resultCartItems) > 0): ?>
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
                                    <th></th> <!-- Cột cho nút xóa -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($resultCartItems)): ?>
                                    <tr data-cart-item-id="<?php echo $item['cart_item_id']; ?>">
                                        <td class="shoping__cart__item">
                                            <img src="img/product/<?php echo htmlspecialchars($item['product_image']); ?>"
                                                alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="50">
                                            <h5><?php echo htmlspecialchars($item['product_name']); ?></h5>
                                        </td>
                                        <td class="shoping__cart__price">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ
                                        </td>
                                        <td class="shoping__cart__quantity">
                                            <div class="quantity">
                                                <div class="pro-qty">
                                                    <input type="number" name="quantity"
                                                        value="<?php echo $item['quantity']; ?>" min="1" required
                                                        data-price="<?php echo $item['price']; ?>">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="shoping__cart__total">
                                            <?php echo number_format($item['total_price'], 0, ',', '.'); ?> VNĐ
                                        </td>
                                        <td class="shoping__cart__item__close">
                                            <a href="includes/remove_from_cart.php?cart_item_id=<?php echo $item['cart_item_id']; ?>"
                                                class="btn btn-danger btn-lg" role="button">
                                                <i class="fas fa-times"></i> <!-- Thêm biểu tượng "X" -->
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $totalAmount += (float) $item['total_price']; ?>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="shop-grid.php" class="primary-btn cart-btn">Tiếp tục mua hàng</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__continue">
                        <div class="shoping__discount">
                            <h5>Mã giảm giá</h5>
                            <form action="#">
                                <input type="text" placeholder="Mã giảm giá">
                                <button type="submit" class="site-btn">Áp mã</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Tổng giỏ hàng</h5>
                        <ul>
                            <li>Tổng tiền <span><?php echo number_format($totalAmount, 0, ',', '.'); ?> VNĐ</span></li>
                        </ul>
                        <a href="checkout.php" class="primary-btn">Thanh toán</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center mt-5">Giỏ hàng của bạn trống.</p>
        <?php endif; ?>
    </div>
</section>

<style>
    .shoping__cart__item__close .btn-danger {
        transition: transform 0.2s ease;
    }

    .shoping__cart__item__close .btn-danger:hover {
        transform: scale(1.1);
        /* Phóng to nút khi hover */
        color: white;
        /* Đảm bảo văn bản có màu trắng khi hover */
    }
</style>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>