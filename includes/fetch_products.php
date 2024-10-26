<?php
include 'db_conn.inc';

ob_start(); 

$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.product_image
        FROM 
            products p ";


if ($filter === 'new') {
    $sql .= "ORDER BY p.created_at DESC LIMIT 8"; 
} elseif ($filter === 'hot') {
    $sql .= "LEFT JOIN orderdetails od ON p.product_id = od.product_id
             GROUP BY p.product_id
             ORDER BY SUM(od.quantity) DESC LIMIT 8"; 
} else {
    $sql .= "ORDER BY product_id DESC LIMIT 8"; 
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

// Hiển thị sản phẩm
while ($row = mysqli_fetch_assoc($result)): 
?>
    <div class="col-lg-3 col-md-4 col-sm-6 mix">
        <div class="featured__item">
            <div class="featured__item__pic">
                <img src="img/product/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100%; height: auto; object-fit: cover;">
                <ul class="featured__item__pic__hover">
                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                </ul>
            </div>
            <div class="featured__item__text">
                <h6><a href="#"><?php echo htmlspecialchars($row['product_name']); ?></a></h6>
                <h5><?php echo '$' . number_format($row['price'], 2); ?></h5>
            </div>
        </div>
    </div>
<?php endwhile;

$content = ob_get_clean(); // Kết thúc output buffering và lưu vào biến $content
echo $content; // Xuất nội dung HTML trực tiếp để AJAX nhận đúng phần tử cần thiết
