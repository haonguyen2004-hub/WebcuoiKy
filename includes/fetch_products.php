<?php
include 'db_conn.inc';

// Bắt đầu bộ đệm đầu ra
ob_start();

// Nhận giá trị bộ lọc từ URL, mặc định là 'all' nếu không có
$filter = $_GET['filter'] ?? 'all';

// Truy vấn SQL cơ bản để lấy sản phẩm
$sql = "SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.product_image,
            d.discount_type,
            d.discount_value,
            CASE
                WHEN d.discount_type = 'percentage' THEN p.price * (1 - d.discount_value / 100)
                WHEN d.discount_type = 'fixed' THEN p.price - d.discount_value
                ELSE p.price
            END AS discounted_price,
            COALESCE(SUM(od.quantity), 0) AS total_sold
        FROM 
            products p
        LEFT JOIN 
            productdiscounts pd ON p.product_id = pd.product_id
        LEFT JOIN 
            discounts d ON pd.discount_id = d.discount_id
            AND d.start_date <= CURDATE() AND d.end_date >= CURDATE()
        LEFT JOIN 
            orderdetails od ON p.product_id = od.product_id ";

// Xử lý bộ lọc
if ($filter === 'new') {
    // Sản phẩm mới nhất
    $sql .= "GROUP BY p.product_id ORDER BY p.created_at DESC LIMIT 8";
} elseif ($filter === 'hot') {
    // Sản phẩm hot: chỉ lấy sản phẩm có giảm giá, sắp xếp theo giá đã giảm
    $sql .= "WHERE d.discount_id IS NOT NULL
             GROUP BY p.product_id
             ORDER BY discounted_price ASC LIMIT 8";
} else {
    // Mặc định là hiển thị các sản phẩm bán chạy nhất
    $sql .= "GROUP BY p.product_id
             ORDER BY total_sold DESC LIMIT 8";
}

// Thực hiện truy vấn
$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

// Hiển thị sản phẩm
while ($row = mysqli_fetch_assoc($result)):
    // Xác định giá hiển thị: giá gốc nếu không giảm giá, giá đã giảm nếu có giảm giá
    $displayPrice = isset($row['discounted_price']) ? $row['discounted_price'] : $row['price'];
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mix">
        <div class="featured__item">
            <div class="featured__item__pic">
                <a href="shop-details.php?id=<?php echo $row['product_id']; ?>">
                    <img src="img/product/<?php echo htmlspecialchars($row['product_image']); ?>"
                        alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                        style="width: 100%; height: auto; object-fit: cover;">
                </a>
                <ul class="featured__item__pic__hover">
                    <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i
                                class="fa-solid fa-eye"></i></a></li>
                    <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                    <li title="Thêm vào giỏ hàng">
                        <a href="javascript:void(0);" onclick="addToCart(<?php echo $row['product_id']; ?>)">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="featured__item__text">
                <h6><a
                        href="shop-details.php?id=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['product_name']); ?></a>
                </h6>

                <?php if (!is_null($row['discount_type'])): ?>
                    <!-- Hiển thị giá gốc và giá đã giảm nếu có giảm giá -->
                    <h5 style="text-decoration: line-through; color: #999;">
                        <?php echo number_format($row['price'], 0) . ' VNĐ'; ?>
                    </h5>
                    <h5 style="color: red;"><?php echo number_format($displayPrice, 0) . ' VNĐ'; ?></h5>
                <?php else: ?>
                    <!-- Hiển thị giá bình thường nếu không giảm giá -->
                    <h5><?php echo number_format($row['price'], 0) . ' VNĐ'; ?></h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endwhile;

// Kết thúc và xuất nội dung
$content = ob_get_clean();
echo $content;
?>