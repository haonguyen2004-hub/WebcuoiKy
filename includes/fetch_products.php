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
            p.product_image
        FROM 
            products p ";

// Xử lý bộ lọc
if ($filter === 'new') {
    $sql .= "ORDER BY p.created_at DESC LIMIT 8";
} elseif ($filter === 'hot') {
    $sql .= "LEFT JOIN orderdetails od ON p.product_id = od.product_id
             GROUP BY p.product_id
             ORDER BY SUM(od.quantity) DESC LIMIT 8";
} else {
    $sql .= "ORDER BY p.product_id DESC LIMIT 8";
}

// Thực hiện truy vấn
$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

// Hiển thị sản phẩm
while ($row = mysqli_fetch_assoc($result)):
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mix">
        <div class="featured__item">
            <div class="featured__item__pic">
                <a href="shop-details.php?id=<?php echo $row['product_id']; ?>"> <img
                        src="img/product/<?php echo htmlspecialchars($row['product_image']); ?>"
                        alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                        style="width: 100%; height: auto; object-fit: cover;">
                </a>
                <ul class="featured__item__pic__hover">

                    <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i
                                class="fa-solid fa-eye"></i></a>
                    </li>
                    <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                    <li title="Thêm vào giỏ hàng"><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                </ul>
            </div>
            <div class="featured__item__text">
                <h6><a
                        href="shop-details.php?id=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['product_name']); ?></a>
                </h6>
                <h5><?php echo number_format($row['price'], 0) . ' VNĐ'; ?></h5>
            </div>
        </div>
    </div>
<?php endwhile;

// Kết thúc và xuất nội dung
$content = ob_get_clean();
echo $content;
?>