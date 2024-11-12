<?php
session_start();
include 'db_conn.inc';

if (!isset($_SESSION['customer_id'])) {
    echo "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
    exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo "Thiếu dữ liệu product_id hoặc quantity!";
    exit;
}

$customerId = $_SESSION['customer_id'];
$productId = (int) $_POST['product_id'];
$quantity = (int) $_POST['quantity'];

// Kiểm tra số lượng tồn kho của sản phẩm
$sqlCheckStock = "SELECT stock_quantity, price FROM products WHERE product_id = ?";
$stmtCheckStock = mysqli_prepare($conn, $sqlCheckStock);
mysqli_stmt_bind_param($stmtCheckStock, "i", $productId);
mysqli_stmt_execute($stmtCheckStock);
$resultCheckStock = mysqli_stmt_get_result($stmtCheckStock);
$product = mysqli_fetch_assoc($resultCheckStock);

if (!$product || $product['stock_quantity'] < $quantity) {
    echo "Sản phẩm đã hết hàng.";
    exit;
}

// Lấy giá gốc của sản phẩm
$originalPrice = $product['price'];

// Kiểm tra sản phẩm có giảm giá không
$sqlCheckDiscount = "
    SELECT d.discount_type, d.discount_value
    FROM productdiscounts pd
    JOIN discounts d ON pd.discount_id = d.discount_id
    WHERE pd.product_id = ? AND d.start_date <= CURDATE() AND d.end_date >= CURDATE()";
$stmtCheckDiscount = mysqli_prepare($conn, $sqlCheckDiscount);
mysqli_stmt_bind_param($stmtCheckDiscount, "i", $productId);
mysqli_stmt_execute($stmtCheckDiscount);
$resultCheckDiscount = mysqli_stmt_get_result($stmtCheckDiscount);
$discount = mysqli_fetch_assoc($resultCheckDiscount);

// Tính giá cuối cùng dựa trên giảm giá (nếu có)
$finalPrice = $originalPrice;
if ($discount) {
    if ($discount['discount_type'] === 'percentage') {
        $finalPrice = $originalPrice * (1 - $discount['discount_value'] / 100);
    } elseif ($discount['discount_type'] === 'fixed') {
        $finalPrice = max(0, $originalPrice - $discount['discount_value']); // Không để giá âm
    }
}

// Kiểm tra nếu giỏ hàng đã tồn tại
$sqlCart = "SELECT cart_id FROM carts WHERE customer_id = ?";
$stmtCart = mysqli_prepare($conn, $sqlCart);
mysqli_stmt_bind_param($stmtCart, "i", $customerId);
mysqli_stmt_execute($stmtCart);
$resultCart = mysqli_stmt_get_result($stmtCart);

if (mysqli_num_rows($resultCart) === 0) {
    // Nếu giỏ hàng chưa tồn tại, tạo mới
    $sqlCreateCart = "INSERT INTO carts (customer_id) VALUES (?)";
    $stmtCreateCart = mysqli_prepare($conn, $sqlCreateCart);
    mysqli_stmt_bind_param($stmtCreateCart, "i", $customerId);
    mysqli_stmt_execute($stmtCreateCart);
    $cartId = mysqli_insert_id($conn);
} else {
    // Nếu đã có giỏ hàng, lấy cart_id
    $cart = mysqli_fetch_assoc($resultCart);
    $cartId = $cart['cart_id'];
}

// Kiểm tra nếu sản phẩm đã có trong giỏ hàng
$sqlCheckItem = "SELECT cart_item_id, quantity FROM cartitems WHERE cart_id = ? AND product_id = ?";
$stmtCheckItem = mysqli_prepare($conn, $sqlCheckItem);
mysqli_stmt_bind_param($stmtCheckItem, "ii", $cartId, $productId);
mysqli_stmt_execute($stmtCheckItem);
$resultCheckItem = mysqli_stmt_get_result($stmtCheckItem);

if (mysqli_num_rows($resultCheckItem) > 0) {
    // Nếu sản phẩm đã có, cập nhật số lượng
    $item = mysqli_fetch_assoc($resultCheckItem);
    $newQuantity = $item['quantity'] + $quantity;

    $sqlUpdateItem = "UPDATE cartitems SET quantity = ?, price = ? WHERE cart_item_id = ?";
    $stmtUpdateItem = mysqli_prepare($conn, $sqlUpdateItem);
    mysqli_stmt_bind_param($stmtUpdateItem, "idi", $newQuantity, $finalPrice, $item['cart_item_id']);
    mysqli_stmt_execute($stmtUpdateItem);
} else {
    // Nếu sản phẩm chưa có, thêm vào giỏ hàng với giá đã giảm (nếu có)
    $sqlAddItem = "INSERT INTO cartitems (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmtAddItem = mysqli_prepare($conn, $sqlAddItem);
    mysqli_stmt_bind_param($stmtAddItem, "iiid", $cartId, $productId, $quantity, $finalPrice);
    mysqli_stmt_execute($stmtAddItem);
}

// Nếu thực hiện thành công, trả về "success"
echo "success";
?>