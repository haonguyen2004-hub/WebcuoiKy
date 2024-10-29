<?php
include 'includes/db_conn.inc';

// Truy vấn SQL để lấy sản phẩm với các thông tin giảm giá
$sql = "SELECT 
    p.product_id,
    p.product_name,
    p.product_image,
    p.price AS original_price,
    d.discount_value,
    d.discount_type
FROM 
    products p
JOIN 
    productdiscounts pd ON p.product_id = pd.product_id
JOIN 
    discounts d ON pd.discount_id = d.discount_id
WHERE 
    CURDATE() BETWEEN d.start_date AND d.end_date;";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $productName = $row['product_name'];
        $productImage = $row['product_image'];
        $originalPrice = number_format($row['original_price'], 0); // Chỉ hiện phần nguyên

        // Tính toán giá sau khi giảm
        if ($row['discount_type'] == 'percentage') {
            $discountedPrice = $row['original_price'] * (1 - $row['discount_value'] / 100);
            $discountValue = $row['discount_value'] . '%';
        } else {
            $discountedPrice = $row['original_price'] - $row['discount_value'];
            $discountValue = number_format($row['discount_value'], 0) . 'VNĐ'; // Chỉ hiện phần nguyên
        }

        $discountedPrice = number_format(floor($discountedPrice), 0); // Làm tròn xuống và bỏ phần thập phân
        ?>

        <div class="col-lg-4">
            <div class="product__discount__item">
                <div class="product__discount__item__pic set-bg"
                    data-setbg="img/product/<?php echo htmlspecialchars($productImage); ?>">
                    <div class="product__discount__percent">-<?php echo $discountValue; ?></div>
                    <ul class="product__item__pic__hover">
                        <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i
                                    class="fa-solid fa-eye"></i></a>
                        </li>
                        <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                        <li title="Thêm vào giỏ hàng"><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                    </ul>
                </div>
                <div class="product__discount__item__text">
                    <span>Sản phẩm giám giá</span>
                    <h5><a href="#"><?php echo htmlspecialchars($productName); ?></a></h5>
                    <div class="product__item__price"><?php echo $discountedPrice; ?> VNĐ
                        <span><?php echo $originalPrice; ?> VNĐ</span>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
} else {
    echo "<p>Không thấy sản phẩm khả dụng</p>";
}
?>