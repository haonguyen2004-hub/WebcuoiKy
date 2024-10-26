<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

// Lấy tất cả sản phẩm để hiển thị ban đầu 
$sql = "SELECT 
    product_id,
    product_name,
    description,
    price,
    stock_quantity,
    product_image,
    category_id
FROM 
    products
ORDER BY 
    product_id DESC
LIMIT 8;
";

$result = mysqli_query($conn, $sql);
?>

<!-- Featured Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Sản phẩm</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="all">Nổi bật</li>
                        <li data-filter="new">Mới nhất</li>
                        <li data-filter="hot">Bán chạy</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php
            // Hiển thị sản phẩm nổi bật mặc định từ truy vấn ban đầu
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix">
                    <div class="featured__item">
                        <div class="featured__item__pic"
                            style="background-image: url('img/product/<?php echo htmlspecialchars($row['product_image']); ?>');">
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
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include "includes/layout.php";
?>