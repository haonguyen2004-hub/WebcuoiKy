<?php
include 'db_conn.inc';

// Bắt đầu bộ đệm đầu ra
ob_start();

// Truy vấn SQL để lấy sản phẩm mới nhất
$sql = "SELECT 
            p.product_id,
            p.product_name,
            p.price,
            p.product_image
        FROM 
            products p 
        ORDER BY p.created_at DESC 
        LIMIT 6";

$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

// Lặp qua kết quả truy vấn và hiển thị sản phẩm
while ($row = mysqli_fetch_assoc($result)):
    $productName = $row['product_name'];
    $productImage = $row['product_image'];
    $productPrice = number_format($row['price'], 0) . ' VNĐ'; // Định dạng giá
?>

<div class="latest-prdouct__slider__item">      
    <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="latest-product__item">
        <div class="latest-product__item__pic">
            <img src="img/product/<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
        </div>
        <div class="latest-product__item__text">
            <h6><?php echo htmlspecialchars($productName); ?></h6>
            <span><?php echo $productPrice; ?></span>
        </div>
    </a>
</div>

<?php endwhile;

// Kết thúc và xuất nội dung từ bộ đệm
$content = ob_get_clean(); 
echo $content;
?>
