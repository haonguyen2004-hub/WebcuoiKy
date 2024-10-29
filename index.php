<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

?>

<?php include "includes/slider.php"; ?>
<?php include "includes/categories.php"; ?>
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
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <?php
                include "includes/fetch_products.php";
                ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include "includes/layout.php";
?>