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
<div class="hero__item set-bg w-100" data-setbg="img/banner/bannerHome.png">
    <div class="hero__text">
    </div>
</div>
</div>
</div>
</div>
</section>
<?php include "includes/categories.php"; ?>
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
                <?php
                echo $content;
                ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include "includes/layout.php";

?>